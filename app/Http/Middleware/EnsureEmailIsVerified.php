<?php

namespace App\Http\Middleware;

use Closure;

class EnsureEmailIsVerified
{
    public function handle($request, Closure $next)
    {
        // 三個判斷
        // 1. 如果會員已經登入
        // 2. 並且還未認證 Email
        // 3. 並且訪問的不是 Email 驗證相關 URL 或者退出的 URL
        if ($request->user() &&
            !$request->user()->hasVerifiedEmail() &&
            !$request->is('email/*', 'logout')) {

            // 根據客戶端返回對應的內容
            return $request->expectsJson()
            ? abort(403, 'Your email adress is not verified.')
            : redirect()->route('verification.notice');
        }

        return $next($request);
    }
}
