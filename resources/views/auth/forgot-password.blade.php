@extends('layouts.app')

@section('title', '忘記密碼')

@section('content')
    <div class="container mx-auto max-w-7xl">
        <div class="min-h-screen flex justify-center items-center px-4 xl:px-0">

            <div class="w-full flex flex-col justify-center items-center">

                {{-- Title --}}
                <div class="fill-current text-gray-700 text-2xl dark:text-white">
                    <i class="bi bi-question-circle"></i><span class="ml-4">忘記密碼</span>
                </div>

                <x-card class="w-full sm:max-w-md mt-4 overflow-hidden">

                    <div class="mb-4 text-gray-600 dark:text-white">
                        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                    </div>

                    {{-- Session 狀態訊息 --}}
                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    {{-- 驗證錯誤訊息 --}}
                    <x-auth-validation-errors class="mb-4" :errors="$errors" />

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        {{-- Email Address --}}
                        <div class="mt-10">
                            <x-floating-label-input
                                :type="'text'"
                                :name="'email'"
                                :placeholder="'Email address'"
                                required
                                autofocus
                            ></x-floating-label-input>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-button>
                                {{ __('Email Password Reset Link') }}
                            </x-button>
                        </div>
                    </form>
                </x-card>


            </div>
        </div>
    </div>
@endsection
