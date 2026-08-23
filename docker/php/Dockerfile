FROM composer:2.10.2 AS composer

FROM php:8.5-fpm-alpine3.24

# Install system packages, compile extensions, and clean up in one layer
# hadolint ignore=DL3018
RUN apk add --no-cache --virtual .build-deps \
        icu-dev \
        libzip-dev \
        unzip \
        zip \
        linux-headers \
        $PHPIZE_DEPS \
    && docker-php-ext-install pdo_mysql intl zip \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && apk del .build-deps

# Copy configuration and dependencies
COPY docker/php/php.ini /usr/local/etc/php/conf.d/99-shawdy.ini
COPY docker/php/xdebug.ini /usr/local/etc/php/conf.d/98-xdebug.ini
COPY --from=composer /usr/bin/composer /usr/bin/composer
COPY docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh

RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["entrypoint.sh"]
