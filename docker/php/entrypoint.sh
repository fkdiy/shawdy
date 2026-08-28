#!/bin/sh

set -e

cd /var/www/html

# Only execute dependency checks during local development
if [ "$APP_ENV" = "dev" ] || [ -z "$APP_ENV" ]; then

    # If composer.json exists but the vendor folder is missing, install dependencies
    if [ -f "composer.json" ] && [ ! -d "vendor" ]; then
        composer install --no-interaction --prefer-dist
    fi
fi

# Ensure the DB engine is actually accepting traffic
until php bin/console doctrine:migrations:status > /dev/null 2>&1; do
  sleep 1
done

php bin/console doctrine:migrations:migrate --no-interaction

if [ "$APP_ENV" = "dev" ]; then
    php bin/console doctrine:migrations:migrate --env=test --no-interaction
fi

exec php-fpm
