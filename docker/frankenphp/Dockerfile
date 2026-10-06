ARG PHP_VERSION=8.5
ARG FRANKENPHP_VERSION=1.12.7
ARG COMPOSER_VERSION=2.10.2


# Composer

FROM composer:${COMPOSER_VERSION} AS composer


# Build custom FrankenPHP binary with caddy-ratelimit

FROM dunglas/frankenphp:${FRANKENPHP_VERSION}-builder-php${PHP_VERSION} AS frankenphp-builder

COPY --from=caddy:builder /usr/bin/xcaddy /usr/bin/xcaddy

RUN CGO_ENABLED=1 \
    XCADDY_SETCAP=1 \
    XCADDY_GO_BUILD_FLAGS="-ldflags='-w -s' -tags=nobadger,nomysql,nopgx" \
    CGO_CFLAGS=$(php-config --includes) \
    CGO_LDFLAGS="$(php-config --ldflags) $(php-config --libs)" \
    xcaddy build \
        --output /usr/local/bin/frankenphp \
        --with github.com/dunglas/frankenphp=./ \
        --with github.com/dunglas/frankenphp/caddy=./caddy/ \
        --with github.com/dunglas/caddy-cbrotli \
        --with github.com/mholt/caddy-ratelimit


# Shared FrankenPHP runtime base

FROM dunglas/frankenphp:${FRANKENPHP_VERSION}-php${PHP_VERSION} AS base

ENV TRUSTED_PROXIES=127.0.0.1

COPY --from=frankenphp-builder \
    /usr/local/bin/frankenphp \
    /usr/local/bin/frankenphp

RUN install-php-extensions \
    pdo_mysql \
    intl \
    zip \
    redis \
    opcache

COPY docker/frankenphp/errors /srv/errors

COPY docker/frankenphp/Caddyfile /etc/frankenphp/Caddyfile
COPY docker/frankenphp/entrypoint.sh /usr/local/bin/entrypoint.sh

RUN chmod +x /usr/local/bin/entrypoint.sh


# Development

FROM base AS development

ENV APP_ENV=dev
ENV COMPOSER_ALLOW_SUPERUSER=1

COPY --from=composer /usr/bin/composer /usr/bin/composer

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
ENV DEFAULT_URI=http://localhost

WORKDIR /app

COPY --from=composer /usr/bin/composer /usr/bin/composer

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


# Build production application

FROM base AS production-builder

ENV APP_ENV=prod
ENV APP_DEBUG=0
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV DEFAULT_URI=http://localhost

WORKDIR /app

COPY --from=composer /usr/bin/composer /usr/bin/composer

COPY apps/backend/ .

RUN composer install \
    --no-dev \
    --no-interaction \
    --no-progress \
    --prefer-dist \
    --optimize-autoloader \
    --classmap-authoritative \
    --no-scripts \
    && php bin/console importmap:install \
    && php bin/console asset-map:compile


# Production runtime

FROM base AS production

ENV APP_ENV=prod
ENV APP_DEBUG=0

WORKDIR /app

ARG USER=appuser

RUN cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

COPY --from=production-builder /app /app

COPY docker/frankenphp/php.production.ini \
    /usr/local/etc/php/conf.d/99-shawdy.ini

RUN <<-EOF
	useradd ${USER}
    setcap -r /usr/local/bin/frankenphp
    mkdir -p /app/var
    chown -R ${USER}:${USER} /config/caddy /data/caddy /app/var
    chmod -R u=rwX,go=rX /app/var
EOF

USER ${USER}

CMD ["frankenphp", "run", "--config", "/etc/frankenphp/Caddyfile"]
