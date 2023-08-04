# Blog

![tests-and-build-images](https://github.com/YilanBoy/blog/actions/workflows/tests-and-build-images.yml/badge.svg)
[![codecov](https://codecov.io/gh/YilanBoy/blog/branch/main/graph/badge.svg?token=K2V2ANX2LW)](https://codecov.io/gh/YilanBoy/blog)

This is a simple blog, mainly used to help me learn about Laravel. The entire project uses
the [TALL stack](https://tallstack.dev/), which is：

- [Tailwind CSS](https://tailwindcss.com/)
- [Alpine.js](https://alpinejs.dev/)
- [Laravel](https://laravel.com/)
- [Laravel Livewire](https://laravel-livewire.com/)

This blog contains certain basic functions, such as membership system, writing articles and replies.

Post editor use [CKEditor 5](https://ckeditor.com/), You can upload image to AWS S3 in blog post. You can search post
by Algolia.

## Requirements

- [php](https://www.php.net/) ^8.1
- [composer](https://getcomposer.org/)
- [npm](https://www.npmjs.com/)

## Installation

Clone the repository to your local machine:

```sh
git clone https://github.com/YilanBoy/blog.git
```

Change the current working directory to the repository:

```sh
cd blog
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

## Service Used

- [Algolia](https://www.algolia.com/): for search post
- [AWS S3](https://aws.amazon.com/tw/s3/): for images storage
- [reCAPTCHA](https://www.google.com/recaptcha/about/): for verify user is bot or not

## Deployment

### Supervisor

You could deploy this project use [Laravel Octane](https://laravel.com/docs/9.x/octane), supercharges the performance by
serving application using [Swoole](https://github.com/swoole/swoole-src).

> **Note**
>
> If you want to use swoole server, you must install swoole extension first.
>
> **Using PECL**:
>
> ```sh
> pecl install swoole
> ```
>
> **Using package manager (Linux)**:
>
> ```sh
> sudo add-apt-repository ppa:ondrej/php
> sudo apt-get php8.1-swoole
> ```

Setting octane in `.env` file

```text
OCTANE_SERVER=swoole
OCTANE_HTTPS=false
```

Start the service by swoole server:

```sh
php artisan ocatane:start
```

In production, you can use [Supervisor](https://github.com/Supervisor/supervisor) to start swoole server and laravel
queue
worker.

Using supervisor to start swoole server process, we have to create a `blog-octane-worker.conf` config file
in `/etc/supervisor/conf.d/`.

```text
[program:blog-octane-worker]
command=/usr/bin/php -d variables_order=EGPCS /var/www/blog/artisan octane:start --workers=2 --server=swoole --host=0.0.0.0 --port=8000
user=www-data
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
redirect_stderr=true
stdout_logfile=/var/log/blog-octane-worker.log
```

Using supervisor to start laravel queue worker process, we have to create a `blog-queue-worker.conf` config file
in `/etc/supervisor/conf.d/`.

```text
[program:blog-queue-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/blog/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stopwaitsecs=3600
stdout_logfile=/var/log/blog-queue-worker.log
```

Set crontab to run [Laravel Task Schedule](https://laravel.com/docs/9.x/scheduling).

Editing crontab.

```sh
crontab -e
```

Add this line to run the Scheduler.

```text
0 * * * * cd /var/www/blog && php artisan schedule:run >> /dev/null 2>&1
```

### Docker

> **Note**
>
> You must install [Docker](https://www.docker.com/) first.

The container is divided into 3 parts, which are app, horizon and scheduler.

Dockerfiles is placed in the folder dockerfiles. The php settings and entrypoint files in the container are placed in
the folder deployment. You can use Docker and these files to build images.

```sh
cd blog

# build blog app
docker buildx build -f dockerfiles/Dockerfile.app --platform linux/amd64,linux/arm64 --push -t blog:latest .

# build horizon
docker buildx build -f dockerfiles/Dockerfile.horizon --platform linux/amd64,linux/arm64 --push -t blog-horizon:latest .

# build scheduler
docker buildx build -f dockerfiles/Dockerfile.scheduler --platform linux/amd64,linux/arm64 --push -t blog-scheduler:latest .
```
