#!/bin/sh
set -e

cd /var/www/html

php bin/console doctrine:migrations:migrate --no-interaction
php bin/console doctrine:migrations:migrate --env=test --no-interaction

exec php-fpm
