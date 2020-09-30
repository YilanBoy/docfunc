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

// 會員身份驗證相關的路由
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// 會員註冊相關的路由
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

// 密碼重置相關的路由
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

// Email 認證相關的路由
Route::get('email/verify', [VerificationController::class, 'show'])->name('verification.notice');
Route::get('email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');
Route::post('email/resend', [VerificationController::class, 'resend'])->name('verification.resend');

// 會員相關頁面
Route::get('users/{user}', [UserController::class, 'show'])->name('users.show');
Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');

// 文章列表與內容
Route::get('posts', [PostController::class, 'index'])->name('posts.index');
Route::get('posts/create', [PostController::class, 'create'])->name('posts.create');
Route::post('posts', [PostController::class, 'store'])->name('posts.store');
Route::get('posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
Route::put('posts/{post}', [PostController::class, 'update'])->name('posts.update');
Route::delete('posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');

// {slug?} 當中的問號代表此參數可給可不給
Route::get('posts/{post}/{slug?}', [PostController::class, 'show'])->name('posts.show');

// 文章分類
Route::get('categories/{category}/{name?}', [CategoryController::class, 'show'])->name('categories.show');

// 會員評論
Route::post('replies', [ReplyController::class, 'store'])->name('replies.store');
Route::delete('replies/{reply}', [ReplyController::class, 'destroy'])->name('replies.destroy');

// 通知列表
Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');

// 文章標籤
Route::get('tags/{tag}', [TagController::class, 'show'])->name('tags.show');

// CKEditor 上傳圖片，使用 CKFinder 上傳至 S3
Route::any('/ckfinder/connector', [CKFinderController::class, 'requestAction'])->name('ckfinder_connector');
// 需要動態更改目錄，使用此連結上傳
// Route::any('/ckfinder/connector', [ImageUploadController::class, 'requestAction'])->name('ckfinder_connector');
