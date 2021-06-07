# Simple-Blog

## Introduction

This is a simple blog made by [Laravel](https://laravel.com/) and [Bootstrap](https://getbootstrap.com/)

The features of this blog include

-   registration
-   login and logout
-   personal pages
-   post publishing
-   post tags
-   comments

Post editor use [CKEditor 5](https://ckeditor.com/), image upload use [CKFinder](https://ckeditor.com/ckfinder/), You can upload image to AWS S3 in blog post.  
You can search post on blog by [Algolia](https://www.algolia.com/)

## Requirements

[php](https://www.php.net/) >= 7.3  
[composer](https://getcomposer.org/)  
[npm](https://www.npmjs.com/)

## Installation

Clone the repository to your local machine, and change the current working directory to the repository

```sh
$ cd personal-blog
```

Install the composer package

```sh
$ composer install
```

Download the ckfinder code

```sh
$ php artisan ckfinder:download
```

Generate ide-helper

```sh
$ php artisan ide-helper:generate
```

Install the npm package

```sh
$ npm install
```

Running laravel mix

```sh
$ npm run production
```

Make a .env file.  
Set up the .env file, such as database connection, reCAPTCHA key, S3 key, mail service and etc.

```sh
$ cp .env-example .env
```

Running migrations

```sh
$ php artisan migrate
```
