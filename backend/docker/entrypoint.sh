#!/bin/sh
set -e

if [ ! -f .env ]; then
    cp .env.example .env
fi

php artisan key:generate --force

touch database/database.sqlite
php artisan migrate --force

chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

exec "$@"
