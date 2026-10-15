#!/usr/bin/env bash
set -Eeuo pipefail
umask 077
source /etc/portfolio-deploy.conf
exec 8>/run/lock/portfolio-poll.lock
flock -n 8 || exit 0

sha="$(git ls-remote "https://github.com/$GITHUB_REPO.git" refs/heads/main | awk '{print $1}')"
[[ "$sha" =~ ^[a-f0-9]{40}$ ]] || exit 0
[[ "$(cat "$STATE_DIR/deployed-sha" 2>/dev/null || true)" != "$sha" ]] || exit 0

# Public repository: no GitHub token, SSH key, or inbound deployment port needed.
response="$(curl --fail --silent --show-error --max-time 20 \
    -H 'Accept: application/vnd.github+json' \
    "https://api.github.com/repos/$GITHUB_REPO/actions/workflows/ci.yml/runs?branch=main&event=push&head_sha=$sha&per_page=10")"
if printf '%s' "$response" | python3 -c '
import json,sys
sha,repo=sys.argv[1:]
runs=json.load(sys.stdin)["workflow_runs"]
latest=max(runs,key=lambda r:r["id"],default=None)
valid=latest and latest["head_sha"]==sha and latest["status"]=="completed" and latest["conclusion"]=="success" and latest["head_repository"]["full_name"]==repo
sys.exit(0 if valid else 1)
' "$sha" "$GITHUB_REPO"; then
    /usr/local/sbin/portfolio-deploy "$sha"
else
    echo "Waiting for successful Portfolio CI on $sha."
fi
