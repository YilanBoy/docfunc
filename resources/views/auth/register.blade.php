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
                        <div class="mt-5">
                            <x-floating-label-input
                                :type="'text'"
                                :name="'name'"
                                :placeholder="'Name'"
                                :value="old('name')"
                                required
                                autofocus
                            ></x-floating-label-input>
                        </div>

                        {{-- Email Address --}}
                        <div class="mt-10">
                            <x-floating-label-input
                                :type="'text'"
                                :name="'email'"
                                :placeholder="'Email address'"
                                :value="old('email')"
                                required
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

                        {{-- Confirm Password --}}
                        <div class="mt-10">
                            <x-floating-label-input
                                :type="'password'"
                                :name="'password_confirmation'"
                                :placeholder="'Confirm password'"
                                required
                            ></x-floating-label-input>
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
