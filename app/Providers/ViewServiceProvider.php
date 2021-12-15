<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // 將分類綁定至上方選單的 View 中
        View::composer(
            ['layouts.header', 'posts.create', 'posts.edit'],
            'App\Http\View\Composers\CategoryComposer'
        );

        // 熱門標籤
        View::composer(
            ['posts.index'],
            'App\Http\View\Composers\PopularTagComposer'
        );

        // 側邊欄學習資源推薦
        View::composer(
            ['posts.index'],
            'App\Http\View\Composers\LinkComposer'
        );
    }
}
