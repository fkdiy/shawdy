FROM composer:2 AS composer

FROM php:8.5-fpm

RUN docker-php-ext-install pdo_mysql

RUN apt-get update \
    && apt-get install -y libicu-dev \
    && docker-php-ext-install intl

COPY --from=composer /usr/bin/composer /usr/bin/composer
