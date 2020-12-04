<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ReplyController;
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
Route::get('users/{user}', [UserController::class, 'show'])->name('users.show');
Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');

// 文章列表與內容
Route::get('posts', [PostController::class, 'index'])->name('posts.index');

Route::middleware(['post.limit', 'verified'])->group(function () {
    Route::get('posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('posts', [PostController::class, 'store'])->name('posts.store');
});

Route::get('posts/{post}/edit', [PostController::class, 'edit'])
    ->middleware('verified')
    ->name('posts.edit');

Route::put('posts/{post}', [PostController::class, 'update'])
    ->middleware('verified')
    ->name('posts.update');

Route::delete('posts/{post}', [PostController::class, 'destroy'])
    ->middleware('verified')
    ->name('posts.destroy');

// {slug?} 當中的問號代表此參數可給可不給
Route::get('posts/{post}/{slug?}', [PostController::class, 'show'])->name('posts.show');

// 文章分類
Route::get('categories/{category}/{name?}', [CategoryController::class, 'show'])->name('categories.show');

// 會員評論
Route::post('replies', [ReplyController::class, 'store'])
    ->middleware('verified')
    ->name('replies.store');

Route::delete('replies/{reply}', [ReplyController::class, 'destroy'])
    ->middleware('verified')
    ->name('replies.destroy');

// 通知列表
Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');

// 文章標籤
Route::get('tags/{tag}', [TagController::class, 'show'])->name('tags.show');

// CKEditor 上傳圖片，使用 CKFinder 上傳至 S3
Route::any('/ckfinder/connector', [CKFinderController::class, 'requestAction'])->name('ckfinder_connector');
// 需要動態更改目錄，使用此連結上傳
// Route::any('/ckfinder/connector', [ImageUploadController::class, 'requestAction'])->name('ckfinder_connector');
