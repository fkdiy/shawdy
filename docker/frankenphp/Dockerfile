# Composer

FROM composer:2.10.2 AS composer


# Shared FrankenPHP base

FROM dunglas/frankenphp:1.12.7-php8.5 AS base

RUN install-php-extensions \
    pdo_mysql \
    intl \
    zip \
    redis \
    opcache

COPY --from=composer /usr/bin/composer /usr/bin/composer

COPY docker/frankenphp/Caddyfile /etc/frankenphp/Caddyfile
COPY docker/frankenphp/entrypoint.sh /usr/local/bin/entrypoint.sh

RUN chmod +x /usr/local/bin/entrypoint.sh


# Development
FROM base AS development

ENV APP_ENV=dev
ENV COMPOSER_ALLOW_SUPERUSER=1

RUN install-php-extensions xdebug

COPY docker/frankenphp/php.development.ini \
    /usr/local/etc/php/conf.d/99-shawdy.ini

ENTRYPOINT ["entrypoint.sh"]

CMD ["frankenphp", "run", "--config", "/etc/frankenphp/Caddyfile"]


# CI
FROM base AS ci

ENV APP_ENV=dev
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV SKIP_DEV_SETUP=1

WORKDIR /app

COPY apps/backend/composer.json apps/backend/composer.lock ./

RUN composer install \
    --no-interaction \
    --no-progress \
    --prefer-dist \
    --no-scripts

COPY apps/backend/ .

RUN composer dump-autoload --optimize \
    && php bin/console importmap:install \
    && php bin/console asset-map:compile

ENTRYPOINT ["entrypoint.sh"]

CMD ["frankenphp", "run", "--config", "/etc/frankenphp/Caddyfile"]


# Production
FROM base AS production

ENV APP_ENV=prod
ENV APP_DEBUG=0

WORKDIR /app

RUN cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

COPY apps/backend/ .

RUN composer install \
    --no-dev \
    --no-interaction \
    --no-progress \
    --prefer-dist \
    --optimize-autoloader \
    --classmap-authoritative \
    && php bin/console importmap:install \
    && php bin/console asset-map:compile

COPY docker/frankenphp/php.production.ini \
    /usr/local/etc/php/conf.d/99-shawdy.ini

ENTRYPOINT ["entrypoint.sh"]

CMD ["frankenphp", "run", "--config", "/etc/frankenphp/Caddyfile"]