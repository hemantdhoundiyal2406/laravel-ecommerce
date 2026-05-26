#!/usr/bin/env bash
set -euo pipefail

export PORT="${PORT:-10000}"

sed -ri "s/^Listen .*/Listen ${PORT}/" /etc/apache2/ports.conf
sed -ri "s/<VirtualHost \*:[0-9]+>/<VirtualHost *:${PORT}>/" /etc/apache2/sites-available/000-default.conf

php artisan optimize:clear
php artisan storage:link || true
php artisan migrate --force
php artisan deploy:seed-if-empty
php artisan config:cache
php artisan route:cache
php artisan view:cache

exec apache2-foreground
