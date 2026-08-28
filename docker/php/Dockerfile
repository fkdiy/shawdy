FROM composer:2.10.2 AS composer


# Common PHP base image shared by development and production.
FROM php:8.5-fpm-alpine3.24 AS php-base

# Tell Composer it is safe to run plugins as root
ENV COMPOSER_ALLOW_SUPERUSER=1

# Install runtime libraries and build dependencies, compile PHP extensions,
# and remove the build dependencies afterwards.
# hadolint ignore=DL3018,SC2086
RUN apk add --no-cache \
        icu \
        libzip \
        unzip \
    && apk add --no-cache --virtual .build-deps \
        icu-dev \
        libzip-dev \
        linux-headers \
        $PHPIZE_DEPS \
    && docker-php-ext-install pdo_mysql intl zip \
    && apk del .build-deps

WORKDIR /var/www/html


# Build the Symfony application and compile its assets.
FROM php-base AS build

COPY --from=composer /usr/bin/composer /usr/bin/composer

COPY . .

RUN rm -rf var/cache

ENV APP_ENV=prod
ENV APP_DEBUG=0

RUN composer install \
        --no-dev \
        --no-interaction \
        --prefer-dist \
        --optimize-autoloader

# hadolint ignore=DL3059
RUN php bin/console asset-map:compile


# Development image.
# Adds Xdebug to the common PHP base image.
FROM php-base AS development

# Xdebug needs the PHP build toolchain and Linux headers to compile.
# hadolint ignore=DL3018,SC2086
RUN apk add --no-cache --virtual .xdebug-build-deps \
        $PHPIZE_DEPS \
        linux-headers \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && apk del .xdebug-build-deps

COPY docker/php/php.ini /usr/local/etc/php/conf.d/99-shawdy.ini
COPY docker/php/xdebug.ini /usr/local/etc/php/conf.d/98-xdebug.ini
COPY docker/php/opcache-dev.ini /usr/local/etc/php/conf.d/97-opcache.ini
COPY --from=composer /usr/bin/composer /usr/bin/composer

COPY docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["entrypoint.sh"]


# Production image.
# Contains the built Symfony application but no development tooling such as Xdebug.
FROM php-base AS production

ENV APP_ENV=prod

COPY --from=build /var/www/html /var/www/html

COPY docker/php/php.ini /usr/local/etc/php/conf.d/99-shawdy.ini
COPY docker/php/opcache-prod.ini /usr/local/etc/php/conf.d/98-opcache.ini

COPY docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["entrypoint.sh"]
