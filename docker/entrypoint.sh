#!/bin/sh
set -eu
mkdir -p writable/cache writable/logs writable/session writable/uploads public/uploads
chown -R www-data:www-data writable
chown www-data:www-data public/uploads
chmod 750 writable writable/cache writable/logs writable/session writable/uploads
chmod 755 public/uploads
exec docker-php-entrypoint "$@"
