FROM composer:2 AS composer

FROM php:8.5-fpm

RUN docker-php-ext-install pdo_mysql

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libicu-dev=76.1-4 \
        libicu76=76.1-4 \
        libzip-dev=1.11.3-2 \
        unzip=6.0-29+deb13u1 \
        zip=3.0-15+deb13u1 \
    && docker-php-ext-install intl \
    && apt-get purge -y libicu-dev \
    && apt-get purge -y --auto-remove libzip-dev \
    && rm -rf /var/lib/apt/lists/*

COPY docker/php/php.ini /usr/local/etc/php/conf.d/99-shawdy.ini

COPY --from=composer /usr/bin/composer /usr/bin/composer

COPY docker/php/entrypoint.sh /usr/local/bin/entrypoint.sh

RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["entrypoint.sh"]
