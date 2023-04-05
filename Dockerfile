ARG PHP_VERSION=8.2

###########################################
# php dependencies
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
# front-end bundle
###########################################

FROM node:latest AS assets

WORKDIR /var/www/html

COPY . .
RUN npm install \
    && npm run build

###########################################
# PHP runtime
###########################################

FROM php:${PHP_VERSION}-cli-bullseye

LABEL maintainer="Allen"

ARG WWWUSER=1000
ARG WWWGROUP=1000

ENV ROOT=/var/www/html
WORKDIR $ROOT

# set the default shell to /bin/bash with some useful options
# -e: exit immediately if a command exits with a non-zero status
# -o: enable POSIX mode for the shell, which ensures that the shell behaves in a standard way
# -u: treat unset variables as an error, and immediately exit if an undefined variable is referenced
# -c: execute the following command when the shell starts
SHELL ["/bin/bash", "-eou", "pipefail", "-c"]

COPY . .

# use --no-install-recommends flag to apt-get in dockerfile to save space
# see https://github.com/jhipster/generator-jhipster/issues/12648
RUN apt-get update \
    && apt-get upgrade -yqq \
    && apt-get install -yqq --no-install-recommends --show-progress \
    supervisor \
    wget

# install php extension
RUN docker-php-ext-install pdo_mysql \
    && docker-php-ext-install opcache \
    && pecl install redis \
    && pecl install swoole \
    && docker-php-ext-install pcntl \
    && docker-php-ext-enable redis swoole

# set supercronic for schedules
# download the corresponding files according to the different architectures.
RUN if [ "$(uname -m)" = "x86_64" ]; then \
    wget -q "https://github.com/aptible/supercronic/releases/download/v0.2.2/supercronic-linux-amd64" \
    -O /usr/bin/supercronic; \
    elif [ "$(uname -m)" = "aarch64" ]; then \
    wget -q "https://github.com/aptible/supercronic/releases/download/v0.2.2/supercronic-linux-arm64" \
    -O /usr/bin/supercronic; \
    else \
    echo "Unsupported platform" && exit 1; \
    fi \
    && chmod +x /usr/bin/supercronic \
    && mkdir -p /etc/supercronic \
    && echo "*/1 * * * * php ${ROOT}/artisan schedule:run --verbose --no-interaction" > /etc/supercronic/laravel

HEALTHCHECK --interval=5s --timeout=3s \
    CMD supercronic -test /etc/supercronic/laravel

# create group and user "octane"
RUN groupadd --force -g $WWWGROUP octane \
    && useradd -ms /bin/bash --no-log-init --no-user-group -g $WWWGROUP -u $WWWUSER octane

# create bootstrap and storage files if they do not exist
# gives the 'octane' user read/write and execute privileges to those files
RUN mkdir -p \
    storage/framework/{sessions,views,cache/data} \
    storage/logs \
    bootstrap/cache \
    && chown -R octane:octane \
    storage \
    bootstrap/cache \
    && chmod -R ug+rwx storage bootstrap/cache

# copy supervisor and php config files into container
COPY deployment/octane/supervisord* /etc/supervisor/conf.d/
COPY deployment/octane/php.ini /usr/local/etc/php/conf.d/octane.ini
COPY deployment/octane/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# set entrypoint to start the laravel octane app
COPY deployment/octane/entrypoint.sh deployment/octane/entrypoint.sh
RUN chmod +x deployment/octane/entrypoint.sh

# copy dependencies from another stage
COPY --from=vendor ${ROOT}/vendor vendor
COPY --from=assets ${ROOT}/public/build public/build

EXPOSE 9000

ENTRYPOINT ["deployment/octane/entrypoint.sh"]

HEALTHCHECK --start-period=5s --interval=2s --timeout=5s --retries=8 CMD php artisan octane:status || exit 1
