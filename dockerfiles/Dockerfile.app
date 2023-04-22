ARG PHP_VERSION=8.2

###########################################
# Laravel Dependencies
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
# Front-End Assets
###########################################

FROM node:latest AS assets

WORKDIR /var/www/html

COPY . .
RUN npm install \
    && npm run build

###########################################
# Laravel Octane
###########################################

FROM php:${PHP_VERSION}-alpine3.17

LABEL maintainer="Allen"

ENV ROOT=/var/www/html
WORKDIR $ROOT

# set the default shell to /bin/ash with some useful options
# -e: exit immediately if a command exits with a non-zero status
# -c: execute the following command when the shell starts
SHELL ["/bin/ash", "-e", "-c"]

COPY . .

# install necessary package to install php extension
RUN apk update \
    && apk upgrade \
    && apk add autoconf gcc g++ make

# install php extension
RUN docker-php-ext-install pdo_mysql \
    && docker-php-ext-install opcache \
    && docker-php-ext-install pcntl \
    && pecl install redis \
    && pecl install swoole \
    && docker-php-ext-enable redis swoole

ARG WWWUSER=1000
ARG WWWGROUP=1000

# create group and user "octane"
RUN addgroup -g $WWWGROUP -S octane || true \
    && adduser -D -h /home/octane -s /bin/ash -G octane -u $WWWUSER octane

# create bootstrap and storage files if they do not exist
# gives the 'octane' user read/write and execute privileges to those files
RUN mkdir -p \
        storage/framework/sessions \
        storage/framework/views \
        storage/framework/cache/data \
        storage/logs \
        bootstrap/cache \
    && chown -R octane:octane \
        storage \
        bootstrap/cache \
    && chmod -R ug+rwx storage bootstrap/cache

# copy php config files into container
COPY deployment/php/php.ini /usr/local/etc/php/conf.d/octane.ini
COPY deployment/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# set scripts to start the laravel octane app
COPY deployment/scripts/app-entrypoint.sh deployment/scripts/app-entrypoint.sh
RUN chmod +x deployment/scripts/app-entrypoint.sh

# copy dependencies from another stage
COPY --from=vendor ${ROOT}/vendor vendor
COPY --from=assets ${ROOT}/public/build public/build

EXPOSE 9000

USER octane

ENTRYPOINT ["deployment/scripts/app-entrypoint.sh"]

HEALTHCHECK --start-period=5s --interval=2s --timeout=5s --retries=8 \
    CMD curl --fail localhost:9000 || exit 1
