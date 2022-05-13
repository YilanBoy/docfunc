# Simple Blog

![build](https://github.com/YilanBoy/simple-blog/actions/workflows/build.yml/badge.svg)
[![codecov](https://codecov.io/gh/YilanBoy/simple-blog/branch/main/graph/badge.svg?token=K2V2ANX2LW)](https://codecov.io/gh/YilanBoy/simple-blog)

This is a simple blog made by [TALL stack](https://tallstack.dev/):

- [Tailwind CSS](https://tailwindcss.com/)
- [Alpine.js](https://alpinejs.dev/)
- [Laravel](https://laravel.com/)
- [Laravel Livewire](https://laravel-livewire.com/)

The features of this blog include:

- authentication
- creating post
- post tags
- comments
- post search
- web feed url
- dark mode

Post editor use [CKEditor 5](https://ckeditor.com/), You can upload image to AWS S3 in blog post. You can search post
by [Algolia](https://www.algolia.com/).

## Preview

<img src="https://recode-blog-files.s3.ap-northeast-2.amazonaws.com/2021_08_21_11_03_46_61206d123c717.jpg" width="70%">

## Requirements

[php](https://www.php.net/) ^8.0  
[composer](https://getcomposer.org/)  
[npm](https://www.npmjs.com/)

## Installation

Clone the repository to your local machine:

```sh
git clone https://github.com/YilanBoy/simple-blog.git
```

Change the current working directory to the repository:

```sh
cd simple-blog
```

Install the composer package:

```sh
composer install
```

Install the npm package:

```sh
npm install
```

Running laravel mix:

```sh
npm run dev
```

Create the `.env` file, and set up the config, such as database connection, reCAPTCHA key, S3 key, mail service etc:

```sh
cp .env-example .env
```

Generate application key:

```sh
php artisan key:generate
```

Running migrations command to generate the database schema:

```sh
php artisan migrate
```

Generate ide-helper:

```sh
php artisan ide-helper:generate
```

Generate model ide-helper:

```sh
php artisan ide-helper:models
```

## Deployment

You could deploy this project use [Laravel Octane](https://laravel.com/docs/9.x/octane), supercharges the performance by
serving application using [Swoole](https://github.com/swoole/swoole-src).

If you want to use swoole server, you must install swoole extension first.

**Using PECL**:

```sh
pecl install swoole
```

**Using package manager (Linux)**:

```sh
sudo add-apt-repository ppa:ondrej/php
sudo apt-get php8.1-swoole
```

Setting octane in `.env` file

```text
OCTANE_SERVER=swoole
OCTANE_HTTPS=false
```

Start the service by swoole server:

```sh
php artisan ocatane:start
```

In production, we use [Supervisor](https://github.com/Supervisor/supervisor) to start swoole server and laravel queue
worker.

Using supervisor to start swoole server process, we have to create a `simple-blog-octane.conf` config file
in `/etc/supervisor/conf.d/`.

```text
[program:simple-blog-octane]
command=/usr/bin/php -d variables_order=EGPCS /var/www/simple-blog/artisan octane:start --workers=2 --server=swoole --host=0.0.0.0 --port=8000
user=www-data
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
redirect_stderr=true
stdout_logfile=/var/log/simple-blog-octane.log
```

Using supervisor to start laravel queue worker process, we have to create a `simple-blog-worker.conf` config file
in `/etc/supervisor/conf.d/`.

```text
[program:simple-blog-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/simple-blog/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stopwaitsecs=3600
stdout_logfile=/var/log/simple-blog-worker.log
```

Set crontab to run [Laravel Task Schedule](https://laravel.com/docs/9.x/scheduling).

Editing crontab.

```sh
crontab -e
```

Add this line to run the Scheduler.

```text
0 * * * * cd /var/www/simple-blog && php artisan schedule:run >> /dev/null 2>&1
```
