<?php

use App\Http\Controllers\User\DestroyUserController;
use App\Http\Livewire\CategoryPage;
use App\Http\Livewire\ChangeUserPasswordPage;
use App\Http\Livewire\DeleteUserPage;
use App\Http\Livewire\EditUserInfoPage;
use App\Http\Livewire\NotificationPage;
use App\Http\Livewire\Posts\Create as PostCreate;
use App\Http\Livewire\Posts\Edit as PostEdit;
use App\Http\Livewire\Posts\Index as PostIndex;
use App\Http\Livewire\Posts\Show as PostShow;
use App\Http\Livewire\TagPage;
use App\Http\Livewire\UserInfoPage\Index as UserInfoPage;
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
Route::get('/', PostIndex::class)->name('root');

require __DIR__.'/auth.php';

// 會員相關頁面
Route::middleware('auth')->prefix('users')->group(function () {
    Route::get('/{user}', UserInfoPage::class)
        ->name('users.index')
        ->withoutMiddleware('auth');

    Route::get('/{user}/edit', EditUserInfoPage::class)->name('users.edit');
    Route::get('/{user}/change-password', ChangeUserPasswordPage::class)->name('users.changePassword');
    Route::get('/{user}/delete', DeleteUserPage::class)->name('users.delete');

    Route::get('/{user}/destroy', DestroyUserController::class)
        ->name('users.destroy')
        ->withoutMiddleware('auth');
});

// 文章列表與內容
Route::prefix('posts')->group(function () {
    Route::get('/', PostIndex::class)->name('posts.index');

    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/create', PostCreate::class)->name('posts.create');
        Route::get('/{post}/edit', PostEdit::class)->name('posts.edit');
    });

    // {slug?} 當中的問號代表參數為選擇性
    Route::get('/{post}/{slug?}', PostShow::class)
        ->name('posts.show');
});

// 文章分類
Route::get('categories/{category}/{name?}', CategoryPage::class)->name('categories.show');

// 文章標籤
Route::get('tags/{tag}', TagPage::class)->name('tags.show');

// 通知列表
Route::get('notifications', NotificationPage::class)->name('notifications.index');

// Web Feed
Route::feeds();
