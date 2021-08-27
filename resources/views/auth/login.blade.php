@extends('layouts.app')

@section('title', '登入')

@section('scriptsInHead')
    {{-- Google reCAPTCHA --}}
    @if (app()->isProduction())
        {{-- async defer 同時使用會優先使用 async，當瀏覽器不支援 async 才會使用 defer --}}
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif
@endsection

@section('content')
    <div class="container mx-auto max-w-7xl">
        <div class="min-h-screen flex justify-center items-center px-4 xl:px-0">

            <div class="w-full flex flex-col justify-center items-center">
                {{-- Title --}}
                <div class="fill-current text-gray-700 text-2xl
                dark:text-white">
                    <i class="bi bi-box-arrow-in-right"></i><span class="ml-4">登入</span>
                </div>

                {{-- Login Form --}}
                <x-card class="w-full sm:max-w-md mt-4 overflow-hidden">

                    {{-- Session 狀態訊息 --}}
                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    {{-- 驗證錯誤訊息 --}}
                    <x-auth-validation-errors class="mb-4" :errors="$errors" />

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        {{-- Email Address --}}
                        <div class="mt-5">
                            <x-floating-label-input
                                :type="'text'"
                                :name="'email'"
                                :placeholder="'Email address'"
                                required
                                autofocus
                            ></x-floating-label-input>
                        </div>

                        {{-- Password --}}
                        <div class="mt-10">
                            <x-floating-label-input
                                :type="'password'"
                                :name="'password'"
                                :placeholder="'Password'"
                                required
                            ></x-floating-label-input>
                        </div>

                        {{-- Remember Me --}}
                        <div class="block mt-4">
                            <label for="remember_me" class="inline-flex items-center">
                                <input id="remember_me" type="checkbox" name="remember"
                                class="form-checkbox rounded border-gray-300 text-indigo-400 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-600 dark:text-white">{{ __('Remember me') }}</span>
                            </label>
                        </div>

                        {{-- reCAPTCHA --}}
                        @if (app()->isProduction())
                            <x-recaptcha />
                        @endif

                        <div class="flex items-center justify-end mt-4">
                            @if (Route::has('password.request'))
                                <a class="text-gray-400 hover:text-gray-700 dark:hover:text-white" href="{{ route('password.request') }}">
                                    {{ __('Forgot your password?') }}
                                </a>
                            @endif

                            <x-button class="ml-3">
                                {{ __('Log in') }}
                            </x-button>
                        </div>
                    </form>
                </x-card>
            </div>

        </div>
    </div>
@endsection
