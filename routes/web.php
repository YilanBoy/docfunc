<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\TagController;
use CKSource\CKFinderBridge\Controller\CKFinderController;

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
    Route::get('/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/{user}', [UserController::class, 'update'])->name('users.update');
});

// 文章列表與內容
Route::prefix('posts')->group(function () {
    Route::get('/', [PostController::class, 'index'])->name('posts.index');

    Route::middleware(['auth', 'verified'])->group(function () {
        Route::view('/create', 'posts/create')->middleware('post.limit')->name('posts.create');
        Route::post('/', [PostController::class, 'store'])->middleware('post.limit')->name('posts.store');
        Route::get('/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
        Route::put('/{post}', [PostController::class, 'update'])->name('posts.update');
        Route::delete('/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    });

    // {slug?} 當中的問號代表此參數可給可不給
    Route::get('/{post}/{slug?}', [PostController::class, 'show'])->name('posts.show');
});
// 顯示軟刪除的文章 (使用 /posts 前綴會返回 404)
Route::get('deleted-posts/{id}', [PostController::class, 'showSoftDeleted'])->name('posts.showSoftDeleted');
// 恢復軟刪除的文章
Route::get('restore-posts/{id}', [PostController::class, 'restorePost'])->name('posts.restorePost');
// 完全刪除文章
Route::delete('force-delete-posts/{id}', [PostController::class, 'forceDeletePost'])->name('posts.forceDeletePost');

// 文章分類
Route::get('categories/{category}/{name?}', [CategoryController::class, 'show'])->name('categories.show');

// 通知列表
Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');

// 文章標籤
Route::get('tags/{tag}', [TagController::class, 'show'])->name('tags.show');

// CKEditor 上傳圖片，使用 CKFinder 上傳至 S3
Route::any('/ckfinder/connector', [CKFinderController::class, 'requestAction'])->name('ckfinder_connector');
