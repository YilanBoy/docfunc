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

FROM node:21 AS assets

WORKDIR /var/www/html

COPY . .
RUN npm install \
    && npm run build

###########################################
# Laravel Octane
###########################################

FROM ubuntu:22.04

LABEL maintainer="Allen"

ENV ROOT=/var/www/html
ENV TZ=Asia/Taipei
WORKDIR $ROOT

# set the default shell to /bin/bash with some useful options
# -e: exit immediately if a command exits with a non-zero status
# -x: print each command before executing it
# -o pipefail: fail if any command in a pipeline fails
# -u: treat unset variables as an error when substituting
SHELL ["/bin/bash", "-exou", "pipefail", "-c"]

# install necessary package to install php extension
RUN apt-get update \
    && apt-get upgrade -y \
    && apt-get install software-properties-common -y

# install php and php extension
RUN add-apt-repository ppa:ondrej/php -y \
    && apt-get update \
    && DEBIAN_FRONTEND=noninteractive apt-get install php8.3 php8.3-{cli,common,curl,xml,mbstring,redis,swoole} -y

ARG WWWUSER=1000
ARG WWWGROUP=1000

# create group and user "octane"
RUN groupadd --force -g $WWWGROUP octane \
    && useradd -ms /bin/bash --no-log-init --no-user-group -g $WWWGROUP -u $WWWUSER octane

# copy php config files into container
COPY deployment/php/php.ini /usr/local/etc/php/conf.d/octane.ini
COPY deployment/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

COPY . .

# set scripts to start the laravel octane app
RUN chmod +x deployment/scripts/app-entrypoint.sh

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

# copy dependencies from another stage
COPY --from=vendor ${ROOT}/vendor vendor
COPY --from=assets ${ROOT}/public/build public/build

EXPOSE 9000

USER octane

ENTRYPOINT ["deployment/scripts/app-entrypoint.sh"]

HEALTHCHECK --start-period=5s --interval=30s --timeout=5s --retries=8 \
    CMD curl --fail localhost:9000 || exit 1
