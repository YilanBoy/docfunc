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
        // 將分類的資料綁定至 View 中
        View::composer(
            ['layouts.header', 'posts.create', 'posts.edit'], 'App\Http\View\Composers\CategoryComposer'
        );

        View::composer(
            ['posts.sidebar'], 'App\Http\View\Composers\LinkComposer'
        );

        View::composer(
            ['posts.create', 'posts.edit'], 'App\Http\View\Composers\TagComposer'
        );
    }
}
