@extends('layouts.app')

@section('title', '登入')

@section('scriptsInHead')
    {{-- Google reCAPTCHA --}}
    @if (app()->isProduction())
        {{-- async defer 同時使用會優先使用 async，當瀏覽器不支援 async 才會使用 defer --}}
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif
@endsection

@section('content')
    <div class="container mx-auto max-w-7xl py-6">
        <div class="flex justify-center items-center px-4 xl:px-0">

            <div class="w-full flex flex-col justify-center items-center">
                {{-- Title --}}
                <div class="fill-current text-gray-700 text-2xl
                dark:text-white">
                    <i class="bi bi-box-arrow-in-right"></i><span class="ml-4">登入</span>
                </div>

                {{-- Login Form --}}
                <x-card class="w-full sm:max-w-md mt-4 overflow-hidden">

                    {{-- Session Status --}}
                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    {{-- Validation Errors --}}
                    <x-auth-validation-errors class="mb-4" :errors="$errors" />

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        {{-- Email Address --}}
                        <div class="relative mt-5">
                            <input
                                id="email"
                                name="email"
                                type="text"
                                placeholder="Email Address"
                                required
                                autofocus
                                class="peer h-10 w-full border-b-2 border-gray-300 text-gray-900
                                placeholder-transparent focus:outline-none focus:border-blue-600
                                dark:bg-gray-600 dark:text-white"
                            >

                            <label
                                for="email"
                                class="absolute left-0 -top-3.5 text-gray-600 text-sm
                                transition-all peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400 peer-placeholder-shown:top-2
                                peer-focus:-top-3.5 peer-focus:text-gray-600 peer-focus:text-sm
                                dark:text-white dark:peer-placeholder-shown:text-white dark:peer-focus:text-white"
                            >
                                Email address
                            </label>
                        </div>

                        {{-- Password --}}
                        <div class="relative mt-10">
                            <input
                                id="password"
                                name="password"
                                type="password"
                                placeholder="Password"
                                required
                                class="peer h-10 w-full border-b-2 border-gray-300 text-gray-900
                                placeholder-transparent focus:outline-none focus:border-blue-600
                                dark:bg-gray-600 dark:text-white"
                            >

                            <label
                                for="password"
                                class="absolute left-0 -top-3.5 text-gray-600 text-sm
                                transition-all peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400 peer-placeholder-shown:top-2
                                peer-focus:-top-3.5 peer-focus:text-gray-600 peer-focus:text-sm
                                dark:text-white dark:peer-placeholder-shown:text-white dark:peer-focus:text-white"
                            >
                                Password
                            </label>
                        </div>

                        {{-- Remember Me --}}
                        <div class="block mt-4">
                            <label for="remember_me" class="inline-flex items-center">
                                <input id="remember_me" type="checkbox" name="remember"
                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
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
