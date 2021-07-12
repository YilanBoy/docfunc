@extends('layouts.app')

@section('title', '註冊')

@section('scriptsInHead')
    {{-- Google reCAPTCHA --}}
    @if (app()->isProduction())
        <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    @endif
@endsection

@section('content')
    <div class="container mx-auto max-w-7xl py-6">
        <div class="flex justify-center items-center px-4 xl:px-0">

            <div class="w-full flex flex-col justify-center items-center">

                {{-- Title --}}
                <div class="fill-current text-gray-700 text-2xl dark:text-white">
                    <i class="bi bi-person-plus-fill"></i><span class="ml-4">註冊</span>
                </div>

                <x-card class="w-full sm:max-w-md mt-4 overflow-hidden">

                    {{-- Validation Errors --}}
                    <x-auth-validation-errors class="mb-4" :errors="$errors" />

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        {{-- Name --}}
                        <div class="relative mt-10">
                            <input
                                id="name"
                                name="name"
                                type="text"
                                placeholder="Name"
                                value="{{ old('name') }}"
                                required
                                autofocus
                                class="peer h-10 w-full border-b-2 border-gray-300 text-gray-900
                                placeholder-transparent focus:outline-none focus:border-blue-600
                                dark:bg-gray-600 dark:text-white"
                            >

                            <label
                                for="name"
                                class="absolute left-0 -top-3.5 text-gray-600 text-sm
                                transition-all peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400 peer-placeholder-shown:top-2
                                peer-focus:-top-3.5 peer-focus:text-gray-600 peer-focus:text-sm
                                dark:text-white dark:peer-placeholder-shown:text-white dark:peer-focus:text-white"
                            >
                                Name
                            </label>
                        </div>

                        {{-- Email Address --}}
                        <div class="relative mt-10">
                            <input
                                id="email"
                                name="email"
                                type="text"
                                placeholder="Email Address"
                                value="{{ old('email') }}"
                                required
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

                        {{-- Confirm Password --}}
                        <div class="relative mt-10">
                            <input
                                id="password_confirmation"
                                name="password_confirmation"
                                type="password"
                                placeholder="Confirm password"
                                required
                                class="peer h-10 w-full border-b-2 border-gray-300 text-gray-900
                                placeholder-transparent focus:outline-none focus:border-blue-600
                                dark:bg-gray-600 dark:text-white"
                            >

                            <label
                                for="password_confirmation"
                                class="absolute left-0 -top-3.5 text-gray-600 text-sm
                                transition-all peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400 peer-placeholder-shown:top-2
                                peer-focus:-top-3.5 peer-focus:text-gray-600 peer-focus:text-sm
                                dark:text-white dark:peer-placeholder-shown:text-white dark:peer-focus:text-white"
                            >
                                Confirm password
                            </label>
                        </div>

                        {{-- reCAPTCHA --}}
                        @if (app()->isProduction())
                            <x-recaptcha />
                        @endif

                        <div class="flex items-center justify-end mt-4">
                            <a class="text-gray-400 hover:text-gray-700 dark:hover:text-white" href="{{ route('login') }}">
                                {{ __('Already registered?') }}
                            </a>

                            <x-button class="ml-4">
                                {{ __('Register') }}
                            </x-button>
                        </div>
                    </form>
                </x-card>
            </div>

        </div>
    </div>
@endsection
