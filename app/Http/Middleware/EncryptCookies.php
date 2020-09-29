<?php

namespace App\Http\Middleware;

use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;

class EncryptCookies extends Middleware
{
    /**
     * The names of the cookies that should not be encrypted.
     *
     * @var array
     */
    protected $except = [
        // CKFinder 已經有自己的 CSRF Protection，因此這裡排除
        'ckCsrfToken',
        //
    ];
}
