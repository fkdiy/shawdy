# Load Composer image to install dependencies
FROM composer:2.10.2 AS composer


# Build a common base image for FrankenPHP
FROM dunglas/frankenphp:1.12.7-php8.5 AS base

RUN install-php-extensions \
    pdo_mysql \
    intl \
    zip \
    opcache

COPY --from=composer /usr/bin/composer /usr/bin/composer

COPY docker/frankenphp/Caddyfile /etc/frankenphp/Caddyfile
COPY docker/frankenphp/entrypoint.sh /usr/local/bin/entrypoint.sh

RUN chmod +x /usr/local/bin/entrypoint.sh


# Development
FROM base AS development

ENV APP_ENV=dev

# Composer is allowed to run as root in the development container.
ENV COMPOSER_ALLOW_SUPERUSER=1

# Install Xdebug for local development
RUN install-php-extensions xdebug

# Copy Xdebug configuration
COPY docker/frankenphp/xdebug.ini /usr/local/etc/php/conf.d/99-xdebug.ini

ENTRYPOINT ["entrypoint.sh"]
CMD ["frankenphp", "run", "--config", "/etc/frankenphp/Caddyfile"]


# Build the Vue.js frontend for production
FROM node:26.7.0-alpine3.24 AS frontend

WORKDIR /app

COPY frontend/package*.json ./
RUN npm ci

COPY frontend/ ./
RUN npm run build


# Production
FROM base AS production

ENV APP_ENV=prod

WORKDIR /app

COPY . .

RUN composer install \
    --no-dev \
    --no-interaction \
    --no-progress \
    --prefer-dist \
    --optimize-autoloader \
    && php bin/console asset-map:compile

# Vue production build
COPY --from=frontend /app/dist ./dist

ENTRYPOINT ["entrypoint.sh"]
CMD ["frankenphp", "run", "--config", "/etc/frankenphp/Caddyfile"]