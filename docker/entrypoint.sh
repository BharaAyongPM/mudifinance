#!/bin/sh
set -e

cd /var/www/html

mkdir -p \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/testing \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

chown -R www-data:www-data storage bootstrap/cache public
chmod -R ug+rwx storage bootstrap/cache

if [ ! -L public/storage ]; then
    ln -snf /var/www/html/storage/app/public /var/www/html/public/storage
fi

exec "$@"
