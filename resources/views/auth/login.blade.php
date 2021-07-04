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
    <main class="container mx-auto max-w-7xl mb-6">
        <div class="flex justify-center items-center px-4 xl:px-0">
            <div class="w-full lg:w-1/3 flex flex-col sm:justify-center items-center bg-gray-100">
                {{-- Logo --}}
                <div class="text-2xl">
                    <i class="bi bi-box-arrow-in-right"></i><span class="ml-4">登入</span>
                </div>

                {{-- Login Form --}}
                <div class="w-full sm:max-w-md mt-4 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">

                    @if ($errors->any())
                        <div class="mb-4">
                            <div class="font-medium text-red-600">
                                {{ __('Whoops! Something went wrong.') }}
                            </div>

                            <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        {{-- Email Address --}}
                        <div>
                            <label for="email" class="block font-medium text-sm text-gray-700">
                                {{ __('Email') }}
                            </label>

                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                            class="block mt-1 w-full rounded-md shadow-sm border-gray-300
                            focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>

                        {{-- Password --}}
                        <div class="mt-4">
                            <label for="password" class="block font-medium text-sm text-gray-700">
                                {{ __('Password') }}
                            </label>

                            <input id="password" type="password" name="password" required autocomplete="current-password"
                            class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        </div>

                        {{-- Remember Me --}}
                        <div class="block mt-4">
                            <label for="remember_me" class="inline-flex items-center">
                                <input id="remember_me" type="checkbox" name="remember"
                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                            </label>
                        </div>

                        {{-- reCAPTCHA --}}
                        @if (app()->isProduction())
                            <div class="mt-4">
                                <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
                            </div>
                        @endif

                        <div class="flex items-center justify-end mt-4">
                            @if (Route::has('password.request'))
                                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                                    {{ __('Forgot your password?') }}
                                </a>
                            @endif

                            <button type="submit" class="ml-3 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md
                            font-semibold text-sm text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none
                            focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                {{ __('Log in') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </main>
@endsection
