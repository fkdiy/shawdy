#!/bin/sh

set -e

cd /app

# Prepare dependencies and assets only for local development.
if [ "$APP_ENV" = "dev" ] && [ "${SKIP_DEV_SETUP:-0}" != "1" ]; then

    # Install dependencies when vendor is missing.
    if [ -f "composer.json" ] && [ ! -d "vendor" ]; then
        composer install --no-interaction --prefer-dist
    fi

    # Install and compile Symfony assets.
    php bin/console importmap:install
    php bin/console asset-map:compile
fi

# Ensure the database is accepting connections.
until php bin/console doctrine:migrations:status; do
    echo "Waiting for database..."
    sleep 1
done

# Ensure the database schema is up to date.
php bin/console doctrine:migrations:migrate --no-interaction

# Prepare the test database during development.
if [ "$APP_ENV" = "dev" ]; then
    php bin/console doctrine:migrations:migrate \
        --env=test \
        --no-interaction
fi

# Ensure the Redis is initialized.
php bin/console app:redirect-read-model:rebuild --if-uninitialized

exec "$@"
