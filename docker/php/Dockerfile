FROM composer:2 AS composer

FROM php:8.5-fpm

# Install system packages, compile extensions, and clean up in one layer
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libicu-dev=76.1-4 \
        libicu76=76.1-4 \
        libzip-dev=1.11.3-2 \
        unzip=6.0-29+deb13u1 \
        zip=3.0-15+deb13u1 \
    && docker-php-ext-install pdo_mysql intl \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    # Clean up dev tools but keep the required libicu76 runtime package
    && apt-get purge -y libicu-dev \
    && apt-get purge -y --auto-remove libzip-dev \
    && rm -rf /var/lib/apt/lists/*

# Copy configuration and dependencies
COPY docker/php/php.ini /usr/local/etc/php/conf.d/99-shawdy.ini
COPY docker/php/xdebug.ini /usr/local/etc/php/conf.d/98-xdebug.ini
COPY --from=composer /usr/bin/composer /usr/bin/composer
COPY docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh

RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["entrypoint.sh"]
