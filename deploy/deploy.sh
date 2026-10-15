#!/usr/bin/env bash
set -Eeuo pipefail
umask 077

source /etc/portfolio-deploy.conf
sha="${1:?Usage: deploy.sh COMMIT_SHA}"
[[ "$sha" =~ ^[a-f0-9]{40}$ ]] || exit 2
exec 9>/run/lock/portfolio-deploy.lock
flock -n 9 || exit 0

cd "$REPO_DIR"
git fetch --quiet origin main
git cat-file -e "$sha^{commit}"
[[ "$(git rev-parse origin/main)" == "$sha" ]] || { echo 'Main changed; waiting for the latest CI run.'; exit 0; }
previous="$(cat "$STATE_DIR/deployed-sha" 2>/dev/null || true)"
[[ "$previous" != "$sha" ]] || exit 0
git checkout --quiet --detach "$sha"
export RELEASE_SHA="$sha"
dc() { docker compose --env-file "$ENV_FILE" "$@"; }

dc config --quiet
dc build app
dc up -d --wait --wait-timeout 180 db

# Every deploy keeps a database snapshot outside the checkout.
mkdir -p "$BACKUP_DIR"
snapshot="$BACKUP_DIR/portfolio-$(date -u +%Y%m%dT%H%M%SZ)-${sha:0:12}.sql.gz"
dc exec -T db sh -c 'exec mariadb-dump -uroot -p"$MARIADB_ROOT_PASSWORD" --single-transaction --quick --routines --triggers portfolio' | gzip > "$snapshot"

rollback_app() {
    if [[ "$previous" =~ ^[a-f0-9]{40}$ ]]; then
        echo "Deployment failed; restoring application image $previous. Database snapshot: $snapshot"
        git checkout --quiet --detach "$previous"
        export RELEASE_SHA="$previous"
        dc up -d --no-deps --no-build --wait --wait-timeout 120 app || true
    fi
}
trap rollback_app ERR
dc run --rm --no-deps app php spark migrate --all
dc up -d --no-deps --no-build --wait --wait-timeout 180 app
curl --fail --silent --show-error --max-time 15 "http://127.0.0.1:${APP_PORT:-18081}/" -o /dev/null
python3 - "$ENV_FILE" "$sha" <<'PY'
import os,pathlib,sys
path=pathlib.Path(sys.argv[1])
lines=[line for line in path.read_text().splitlines() if not line.startswith('RELEASE_SHA=')]
temporary=path.with_suffix('.tmp')
temporary.write_text('\n'.join(lines)+f'\nRELEASE_SHA={sys.argv[2]}\n')
os.chmod(temporary,0o600)
temporary.replace(path)
PY
printf '%s\n' "$sha" > "$STATE_DIR/deployed-sha.tmp"
mv "$STATE_DIR/deployed-sha.tmp" "$STATE_DIR/deployed-sha"
trap - ERR
echo "Deployed $sha successfully."
