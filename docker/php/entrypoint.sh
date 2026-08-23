#!/bin/sh

set -e

cd /var/www/html

php bin/console doctrine:migrations:migrate --no-interaction

if [ "$APP_ENV" = "dev" ]; then
    php bin/console doctrine:migrations:migrate --env=test --no-interaction
fi

exec php-fpm
