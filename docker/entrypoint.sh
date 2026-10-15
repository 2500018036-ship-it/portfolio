#!/bin/sh
set -eu
mkdir -p writable/cache writable/logs writable/session writable/uploads public/uploads
chown www-data:www-data writable writable/cache writable/logs writable/session writable/uploads public/uploads
exec docker-php-entrypoint "$@"
