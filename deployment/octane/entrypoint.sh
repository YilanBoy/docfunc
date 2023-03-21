#!/usr/bin/env bash
set -e

php() {
    su octane -c "php $*"
}

initialStuff() {
    php artisan optimize:clear
    php artisan package:discover --ansi
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
}

initialStuff
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.app.conf
