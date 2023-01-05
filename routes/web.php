<?php

use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\User\ChangePasswordController;
use App\Http\Controllers\User\DeleteUserController;
use App\Http\Controllers\User\SendDestroyUserEmailController;
use App\Http\Controllers\User\UserController;
use App\Http\Livewire\Categories\Show as CategoryShow;
use App\Http\Livewire\Posts\Create as PostCreate;
use App\Http\Livewire\Posts\Edit as PostEdit;
use App\Http\Livewire\Posts\Index as PostIndex;
use App\Http\Livewire\Posts\Show as PostShow;
use App\Http\Livewire\Tags\Show as TagShow;
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
Route::prefix('users')->group(function () {
    Route::get('/{user}', [UserController::class, 'index'])->name('users.index');
    Route::get('/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/{user}', [UserController::class, 'update'])->name('users.update');
    Route::get('/{user}/delete', [DeleteUserController::class, 'index'])->name('users.delete');
    Route::get('/{user}/destroy', [UserController::class, 'destroy'])->name('users.destroy');

    Route::get('/{user}/change-password', [ChangePasswordController::class, 'edit'])->name('users.changePassword');
    Route::put('/{user}/change-password', [ChangePasswordController::class, 'update'])->name('users.updatePassword');

    Route::post('/{user}/send-destroy-email', [SendDestroyUserEmailController::class, 'store'])
        ->name('users.sendDestroyEmail');
});

// 文章列表與內容
Route::prefix('posts')->group(function () {
    Route::get('/', PostIndex::class)->name('posts.index');

    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('/create', PostCreate::class)->name('posts.create');
        Route::get('/{id}/edit', PostEdit::class)->name('posts.edit');
        Route::delete('/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    });

    // {slug?} 當中的問號代表參數為選擇性
    Route::get('/{post}/{slug?}', PostShow::class)
        ->missing(fn () => redirect()->route('posts.index'))
        ->name('posts.show');
});

// 文章分類
Route::get('categories/{category}/{name?}', CategoryShow::class)->name('categories.show');

// 文章標籤
Route::get('tags/{tag}', TagShow::class)->name('tags.show');

// 通知列表
Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');

// Web Feed
Route::feeds();
