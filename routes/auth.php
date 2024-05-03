<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Middleware\CheckRegistrationIsValid;
use App\Livewire\Pages\Auth\ForgotPassword;
use App\Livewire\Pages\Auth\Login;
use App\Livewire\Pages\Auth\Register;
use App\Livewire\Pages\Auth\ResetPassword;
use App\Livewire\Pages\Auth\VerifyEmail;
use Illuminate\Support\Facades\Route;

Route::get('/login', Login::class)
    ->middleware('guest')
    ->name('login');

Route::get('/verify-email', VerifyEmail::class)
    ->middleware('auth')
    ->name('verification.notice');

Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
    ->middleware(['auth', 'signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::get('/register', Register::class)
    ->middleware(['guest', CheckRegistrationIsValid::class])
    ->name('register');

Route::get('/forgot-password', ForgotPassword::class)
    ->middleware('guest')
    ->name('password.request');

Route::get('/reset-password/{token}', ResetPassword::class)
    ->middleware('guest')
    ->name('password.reset');
