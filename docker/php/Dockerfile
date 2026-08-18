FROM composer:2 AS composer

FROM php:8.5-fpm

RUN docker-php-ext-install pdo_mysql

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libicu-dev=76.1-4 \
        libicu76=76.1-4 \
        libzip-dev \
        unzip \
        zip \
    && docker-php-ext-install intl \
    && apt-get purge -y libicu-dev \
    && apt-get purge -y --auto-remove libzip-dev \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer /usr/bin/composer /usr/bin/composer
