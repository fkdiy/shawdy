#!/bin/sh
set -e

cd /var/www/html

php bin/console doctrine:migrations:migrate --no-interaction

exec php-fpm
