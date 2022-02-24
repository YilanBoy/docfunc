# Simple-Blog

<img src="https://recode-blog-files.s3.ap-northeast-2.amazonaws.com/2021_08_21_11_03_46_61206d123c717.jpg" width="70%">

## Introduction

This is a simple blog made by [TALL stack](https://tallstack.dev/)

TALL stack includes

-   [Laravel](https://laravel.com/)
-   [Livewire](https://laravel-livewire.com/)
-   [Tailwind CSS](https://tailwindcss.com/)
-   [Alpine.js](https://alpinejs.dev/)

The features of this blog include

-   authentication
-   create post
-   post tags
-   multiple layer comments
-   post search

Post editor use [CKEditor 5](https://ckeditor.com/), You can upload image to AWS S3 in blog post.  
You can search post by [Algolia](https://www.algolia.com/)

## Requirements

[php](https://www.php.net/) ^8.0  
[composer](https://getcomposer.org/)  
[npm](https://www.npmjs.com/)

## Installation

Clone the repository to your local machine, and change the current working directory to the repository

```sh
$ cd simple-blog
```

Install the composer package

```sh
$ composer install
```

Install the npm package

```sh
$ npm install
```

Running laravel mix

```sh
$ npm run dev
```

Create the **.env** file, and set up the config, such as database connection, reCAPTCHA key, S3 key, mail service etc.

```sh
$ cp .env-example .env
```

Generate application key.
```sh
$ php artisan key:generate
```

Running migrations

```sh
$ php artisan migrate
```

Generate ide-helper

```sh
$ php artisan ide-helper:generate
```

Generate model ide-helper

```sh
$ php artisan ide-helper:models
```
