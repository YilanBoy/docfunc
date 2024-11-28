<p align="center">
  <picture>
    <source media="(prefers-color-scheme: dark)" srcset="https://blobs.docfunc.com/docfunc-dark-badge.png" width="30%">
    <img alt="Badge changing depending on mode." src="https://blobs.docfunc.com/docfunc-light-badge.png" width="30%">
  </picture>
</p>

<p align="center">
  <img src="https://github.com/YilanBoy/docfunc/actions/workflows/tests.yaml/badge.svg" alt="Tests">
  <a href="https://codecov.io/gh/YilanBoy/docfunc" > 
    <img src="https://codecov.io/gh/YilanBoy/docfunc/graph/badge.svg?token=K2V2ANX2LW" alt="Codecov"/> 
  </a>
</p>

## Introduction

This is a simple blog project, mainly used to help me learn about Laravel.
The entire project uses the [TALL stack](https://tallstack.dev/), which is:

- [Tailwind CSS](https://tailwindcss.com/)
- [Alpine.js](https://alpinejs.dev/)
- [Laravel](https://laravel.com/)
- [Laravel Livewire](https://livewire.laravel.com/)

This project contains certain basic functions, such as membership system, writing articles and replies.

Post editor use [CKEditor 5](https://ckeditor.com/), You can upload image to AWS S3 in blog post.
You can search post by [Algolia](https://www.algolia.com/).

## Requirements

- [php](https://www.php.net/) ^8.4
- [composer](https://getcomposer.org/)
- [npm](https://www.npmjs.com/)

## Installation

Clone the repository to your local machine:

```sh
git clone https://github.com/YilanBoy/docfunc.git
```

Change the current working directory to the repository:

```sh
cd docfunc
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

Create the `.env` file, and set up the config, such as database connection, reCAPTCHA key, S3 key, mail service etc.:

```sh
cp .env-example .env
```

Generate application key (for session and cookie encryption):

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

## Service Used

- [Algolia](https://www.algolia.com/): for search post
- [AWS S3](https://aws.amazon.com/s3/): for images storage
- [Turnstile](https://www.cloudflare.com/products/turnstile/): for verify user is bot or not

## Deployment

### Supervisor

You could deploy this project use [Laravel Octane](https://laravel.com/docs/9.x/octane),
supercharges the performance by serving application
using [Swoole](https://github.com/swoole/swoole-src), [RoadRunner](https://roadrunner.dev/),
or [FrankenPHP](https://frankenphp.dev/).

> [!NOTE]
>
> If you want to use swoole server, you must install swoole extension first.
>
> Using PECL to install swoole extension:
>
> ```sh
> pecl install swoole
> ```
>
> Using package manager to install swoole extension (Linux):
>
> ```sh
> sudo add-apt-repository ppa:ondrej/php
> sudo apt-get php8.2-swoole
> ```

Setting octane in `.env` file:

```text
OCTANE_SERVER=swoole
OCTANE_HTTPS=false
```

Start the service by swoole server:

```sh
php artisan ocatane:start
```

In production, you can use [Supervisor](https://github.com/Supervisor/supervisor) to start swoole server and laravel
queue worker.

Using supervisor to start swoole server process,
we have to create a `docfunc-octane-worker.conf` config file in `/etc/supervisor/conf.d/`.

```text
[program:docfunc-octane-worker]
command=/usr/bin/php -d variables_order=EGPCS /var/www/docfunc/artisan octane:start --workers=2 --server=swoole --host=0.0.0.0 --port=8000
user=www-data
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
redirect_stderr=true
stdout_logfile=/var/log/docfunc-octane-worker.log
```

Using supervisor to start laravel queue worker process,
we have to create a `docfunc-queue-worker.conf` config file in `/etc/supervisor/conf.d/`.

```text
[program:docfunc-queue-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/docfunc/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stopwaitsecs=3600
stdout_logfile=/var/log/docfunc-queue-worker.log
```

Set crontab to run [Laravel Task Schedule](https://laravel.com/docs/9.x/scheduling).

Editing crontab.

```sh
crontab -e
```

Add this line to run the Scheduler.

```text
0 * * * * cd /var/www/docfunc && php artisan schedule:run >> /dev/null 2>&1
```
