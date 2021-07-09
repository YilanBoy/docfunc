<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostLimit
{
    /**
     * 確認文章發布的次數限制
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // 一天內只能新增三篇文章
        if (auth()->user()->posts()->whereDate('created_at', today())->count() > 2) {
            return redirect()->route('root')->with('warning', '已達到今日新增文章上限（3篇）');
        }

        return $next($request);
    }
}
