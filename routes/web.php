<?php

use App\Http\Controllers\User\DestroyUserController;
use App\Http\Livewire\Categories\Show as CategoryShow;
use App\Http\Livewire\Notifications\Index as NotificationIndex;
use App\Http\Livewire\Posts\Create as PostCreate;
use App\Http\Livewire\Posts\Edit as PostEdit;
use App\Http\Livewire\Posts\Index as PostIndex;
use App\Http\Livewire\Posts\Show as PostShow;
use App\Http\Livewire\Tags\Show as TagShow;
use App\Http\Livewire\Users\Edit\ChangePassword;
use App\Http\Livewire\Users\Edit\DeleteUser;
use App\Http\Livewire\Users\Edit\EditInformation as UserEditInformation;
use App\Http\Livewire\Users\Information\Index as UserInformationIndex;
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
    Route::get('/{user}', UserInformationIndex::class)
        ->name('users.index')
        ->withoutMiddleware('auth');

    Route::get('/{user}/edit', UserEditInformation::class)->name('users.edit');
    Route::get('/{user}/change-password', ChangePassword::class)->name('users.changePassword');
    Route::get('/{user}/delete', DeleteUser::class)->name('users.delete');

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
        ->missing(fn () => redirect()->route('posts.index'))
        ->name('posts.show');
});

// 文章分類
Route::get('categories/{category}/{name?}', CategoryShow::class)->name('categories.show');

// 文章標籤
Route::get('tags/{tag}', TagShow::class)->name('tags.show');

// 通知列表
Route::get('notifications', NotificationIndex::class)->name('notifications.index');

// Web Feed
Route::feeds();
