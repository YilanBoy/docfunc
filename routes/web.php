<?php

use App\Http\Controllers\User\DestroyUserController;
use App\Livewire\Categories\ShowCategoryPage;
use App\Livewire\Notifications\NotificationsPage;
use App\Livewire\Posts\CreatePostPage;
use App\Livewire\Posts\EditPostPage;
use App\Livewire\Posts\PostsPage;
use App\Livewire\Posts\ShowPostPage;
use App\Livewire\Tags\ShowTagPage;
use App\Livewire\Users\Edit\ChangeUserPasswordPage;
use App\Livewire\Users\Edit\DeleteUserPage;
use App\Livewire\Users\Edit\EditUserInformationPage;
use App\Livewire\Users\Information\UserInformationPage;
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
Route::get('/', PostsPage::class)->name('root');

require __DIR__.'/auth.php';

// 會員相關頁面
Route::middleware('auth')->prefix('users')->group(function () {
    Route::get('/{user}', UserInformationPage::class)
        ->name('users.index')
        ->withoutMiddleware('auth');

    Route::get('/{user}/edit', EditUserInformationPage::class)->name('users.edit');
    Route::get('/{user}/change-password', ChangeUserPasswordPage::class)->name('users.changePassword');
    Route::get('/{user}/delete', DeleteUserPage::class)->name('users.delete');

    Route::get('/{user}/destroy', DestroyUserController::class)
        ->name('users.destroy')
        ->withoutMiddleware('auth');
});

// 文章列表與內容
Route::prefix('posts')->group(function () {
    Route::get('/', PostsPage::class)->name('posts.index');

    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/create', CreatePostPage::class)->name('posts.create');
        Route::get('/{id}/edit', EditPostPage::class)->name('posts.edit');
    });

    // {slug?} 當中的問號代表參數為選擇性
    Route::get('/{post}/{slug?}', ShowPostPage::class)
        ->missing(fn () => redirect()->route('posts.index'))
        ->name('posts.show');
});

// 文章分類
Route::get('categories/{category}/{name?}', ShowCategoryPage::class)->name('categories.show');

// 文章標籤
Route::get('tags/{tag}', ShowTagPage::class)->name('tags.show');

// 通知列表
Route::get('notifications', NotificationsPage::class)->name('notifications.index');

// Web Feed
Route::feeds();
