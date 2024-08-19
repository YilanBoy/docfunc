ARG PHP_VERSION=8.3
ARG SUPERCRONIC_VERSION=0.2.30

###########################################
# PHP Dependencies
###########################################
FROM composer:latest AS vendor

WORKDIR /var/www/html

# copy only the 'composer.json' and 'composer.lock' files into the container
COPY composer* ./
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --ignore-platform-reqs \
    --optimize-autoloader \
    --apcu-autoloader \
    --ansi \
    --no-scripts \
    --audit

###########################################
# Scheduler
###########################################
FROM php:${PHP_VERSION}-alpine3.20
ARG SUPERCRONIC_VERSION

LABEL maintainer="Allen"

ENV ROOT=/var/www/html
WORKDIR $ROOT

# set the default shell to /bin/ash with some useful options
# -e: exit immediately if a command exits with a non-zero status
# -c: execute the following command when the shell starts
SHELL ["/bin/ash", "-e", "-c"]

# install necessary package to install php extension
RUN apk update \
    && apk upgrade \
    && apk add autoconf gcc g++ make

# install php extension
RUN docker-php-ext-install pdo_mysql \
    && docker-php-ext-install opcache \
    && docker-php-ext-install pcntl \
    && pecl install redis \
    && docker-php-ext-enable redis

ARG WWWUSER=1001
ARG WWWGROUP=1001

# create group and user "scheduler"
RUN addgroup -g $WWWGROUP -S scheduler || true \
    && adduser -D -h /home/scheduler -s /bin/ash -G scheduler -u $WWWUSER scheduler

# copy supervisor and php config files into container
COPY containerize/php/php.ini /usr/local/etc/php/conf.d/scheduler.ini
COPY containerize/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# set supercronic for schedules
# download the corresponding files according to the different architectures.
RUN if [ "$(uname -m)" = "x86_64" ]; then \
        wget -q "https://github.com/aptible/supercronic/releases/download/v${SUPERCRONIC_VERSION}/supercronic-linux-amd64" \
            -O /usr/bin/supercronic; \
    elif [ "$(uname -m)" = "aarch64" ]; then \
        wget -q "https://github.com/aptible/supercronic/releases/download/v${SUPERCRONIC_VERSION}/supercronic-linux-arm64" \
            -O /usr/bin/supercronic; \
    else \
        echo "Unsupported platform" && exit 1; \
    fi \
    && chmod +x /usr/bin/supercronic \
    && mkdir -p /etc/supercronic \
    && echo "*/1 * * * * php ${ROOT}/artisan schedule:run --verbose --no-interaction" > /etc/supercronic/laravel

COPY . .

# create bootstrap and storage files if they do not exist
# gives the 'scheduler' user read/write and execute privileges to those files
RUN mkdir -p \
        storage/framework/sessions \
        storage/framework/views \
        storage/framework/cache/data \
        storage/logs \
        bootstrap/cache \
    && chown -R scheduler:scheduler \
        storage \
        bootstrap/cache \
    && chmod -R ug+rwx storage bootstrap/cache

# copy dependencies from another stage
COPY --from=vendor ${ROOT}/vendor vendor

HEALTHCHECK --start-period=5s --interval=30s --timeout=3s \
    CMD supercronic -test /etc/supercronic/laravel

USER scheduler

ENTRYPOINT ["supercronic", "/etc/supercronic/laravel"]
