#!/bin/sh
set -e

php artisan optimize:clear

if [ "${DB_CONNECTION}" = "mysql" ]; then
    echo "Waiting for MySQL at ${DB_HOST}:${DB_PORT:-3306}..."

    until mysqladmin ping \
        --host="${DB_HOST}" \
        --port="${DB_PORT:-3306}" \
        --user="${DB_USERNAME}" \
        --password="${DB_PASSWORD}" \
        --silent; do
        sleep 2
    done
fi

php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache

exec supervisord -c /etc/supervisord.conf
