<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\DestroyEmailController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\ImageController;

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
Route::get('/', [PostController::class, 'index'])->name('root');

require __DIR__ . '/auth.php';

// 會員相關頁面
Route::prefix('users')->group(function () {
    Route::get('/{user}', [UserController::class, 'index'])->name('users.index');
    Route::get('/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/{user}', [UserController::class, 'update'])->name('users.update');
    Route::get('/{user}/delete', [UserController::class, 'delete'])->name('users.delete');
    Route::get('/{user}/destroy', [UserController::class, 'destroy'])->name('users.destroy');

    Route::get('/{user}/change-password', [ChangePasswordController::class, 'edit'])->name('users.changePassword');
    Route::put('/{user}/change-password', [ChangePasswordController::class, 'update'])->name('users.updatePassword');

    Route::post('/{user}/send-destroy-email', [DestroyEmailController::class, 'store'])->name('users.sendDestroyEmail');
});

// 文章列表與內容
Route::prefix('posts')->group(function () {
    Route::get('/', [PostController::class, 'index'])->name('posts.index');

    Route::middleware(['auth', 'verified'])->group(function () {
        Route::view('/create', 'posts/create')->name('posts.create');
        Route::post('/', [PostController::class, 'store'])->name('posts.store');
        Route::get('/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
        Route::put('/{post}', [PostController::class, 'update'])->name('posts.update');
        Route::delete('/{post}', [PostController::class, 'softDelete'])->name('posts.softDelete');
        // 恢復軟刪除的文章
        Route::post('/{id}/restore', [PostController::class, 'restore'])->name('posts.restore');
        // 完全刪除文章
        Route::delete('/{id}/destroy', [PostController::class, 'destroy'])->name('posts.destroy');
    });

    // {slug?} 當中的問號代表此參數可給可不給
    Route::get('/{post}/{slug?}', [PostController::class, 'show'])->name('posts.show');
});

// 文章分類
Route::get('categories/{category}/{name?}', [CategoryController::class, 'show'])->name('categories.show');

// 通知列表
Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');

// 文章標籤
Route::get('tags/{tag}', [TagController::class, 'show'])->name('tags.show');

// 上傳圖片至 S3
Route::post('/images/upload', [ImageController::class, 'store'])->middleware(['auth', 'verified'])->name('images.store');
