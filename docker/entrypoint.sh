#!/bin/sh

if [ ! -d /var/www/vendor ]; then
    composer install
fi

chown -R www-data:1000 /var/www/storage/
chmod -R 775 /var/www/storage/

exec docker-php-entrypoint "$@"
