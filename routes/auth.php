<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Middleware\CheckRegistrationIsValid;
use App\Livewire\Pages\Auth\ForgotPasswordPage;
use App\Livewire\Pages\Auth\LoginPage;
use App\Livewire\Pages\Auth\RegisterPage;
use App\Livewire\Pages\Auth\ResetPasswordPage;
use App\Livewire\Pages\Auth\VerifyEmailPage;
use Illuminate\Support\Facades\Route;

Route::get('/login', LoginPage::class)
    ->middleware('guest')
    ->name('login');

Route::get('/verify-email', VerifyEmailPage::class)
    ->middleware('auth')
    ->name('verification.notice');

Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
    ->middleware(['auth', 'signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::get('/register', RegisterPage::class)
    ->middleware(['guest', CheckRegistrationIsValid::class])
    ->name('register');

Route::get('/forgot-password', ForgotPasswordPage::class)
    ->middleware('guest')
    ->name('password.request');

Route::get('/reset-password/{token}', ResetPasswordPage::class)
    ->middleware('guest')
    ->name('password.reset');
