#!/bin/sh

set -e

cd /app

# Only execute dependency checks during local development
if [ "$APP_ENV" = "dev" ] || [ -z "$APP_ENV" ]; then

    # If composer.json exists but the vendor folder is missing, install dependencies
    if [ -f "composer.json" ] && [ ! -d "vendor" ]; then
        composer install --no-interaction --prefer-dist
    fi

    # Compile Symfony's asset map so that mapped assets are available under public/assets.
    php bin/console asset-map:compile
fi

# Ensure the DB engine is actually accepting traffic
until php bin/console doctrine:migrations:status; do
    echo "Waiting for database..."
    sleep 1
done

# Run migrations to ensure the database schema is up to date
php bin/console doctrine:migrations:migrate --no-interaction

# If the application is running in development mode, also run migrations for the test environment
if [ "$APP_ENV" = "dev" ]; then
    php bin/console doctrine:migrations:migrate --env=test --no-interaction
fi

exec "$@"
