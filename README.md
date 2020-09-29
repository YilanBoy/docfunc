# RecodeBlog

This is a simple blog made by [Laravel](https://laravel.com/) and [Bootstrap](https://getbootstrap.com/)  
The features of this blog include login and logout, registration, post publishing, tags, comments and personal pages  
Post WYSIWYG editor use [CKEditor 5](https://ckeditor.com/), image upload use AWS S3

### Installation

Clone the repository to your local machine, and change the current working directory to the repository

```sh
$ cd recodeblog
```

Install the composer package

```sh
$ composer install
```

download the ckfinder code

```sh
$ php artisan ckfinder:download
```

Install the npm package

```sh
$ npm install
```

Running laravel mix

```sh
$ npm run dev
```

Make a .env file

```sh
$ cp .env-example .env
```

Set up the .env file, such as database connection, reCAPTCHA key, S3 key, mail service and etc.
