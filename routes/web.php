<?php

use App\Http\Controllers\User\DestroyUserController;
use App\Livewire\Pages;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

// 首頁
Route::get('/', Pages\Posts\Index::class)->name('root');

require __DIR__.'/auth.php';

// 會員相關頁面
Route::middleware('auth')->prefix('users')->group(function () {
    Route::get('/{user}', Pages\Users\Show::class)
        ->name('users.show')
        ->withoutMiddleware('auth');

    Route::get('/{user}/edit', Pages\Users\Edit::class)->name('users.edit');
    Route::get('/{user}/change-password', Pages\Users\UpdatePassword::class)->name('users.updatePassword');
    Route::get('/{user}/delete', Pages\Users\Delete::class)->name('users.delete');

    Route::get('/{user}/destroy', DestroyUserController::class)
        ->name('users.destroy')
        ->withoutMiddleware('auth');
});

// 文章列表與內容
Route::prefix('posts')->group(function () {
    Route::get('/', Pages\Posts\Index::class)->name('posts.index');

    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/create', Pages\Posts\Create::class)->name('posts.create');
        Route::get('/{post}/edit', Pages\Posts\Edit::class)->name('posts.edit');
    });

    // {slug?} 當中的問號代表參數為選擇性
    Route::get('/{post}/{slug?}', Pages\Posts\Show::class)->name('posts.show');
});

// 文章分類
Route::get('categories/{category}/{name?}', Pages\Categories\Show::class)->name('categories.show');

// 文章標籤
Route::get('tags/{tag}', Pages\Tags\Show::class)->name('tags.show');

// 通知列表
Route::get('notifications', Pages\Notifications\Index::class)->name('notifications.index');

// Web Feed
Route::feeds();
