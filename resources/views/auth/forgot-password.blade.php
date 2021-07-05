@extends('layouts.app')

@section('title', '忘記密碼')

@section('content')
    <div class="container mx-auto max-w-7xl py-6">
        <div class="flex justify-center items-center px-4 xl:px-0">

            <div class="w-full flex flex-col justify-center items-center bg-gray-100">

                {{-- Title --}}
                <div class="fill-current text-gray-700 text-2xl">
                    <i class="bi bi-question-circle"></i><span class="ml-4">忘記密碼</span>
                </div>

                <div class="w-full sm:max-w-md mt-4 px-6 py-4 bg-white shadow-md overflow-hidden rounded-xl ring-1 ring-black ring-opacity-20">

                    <div class="mb-4 text-gray-600">
                        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                    </div>

                    {{-- Session Status --}}
                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    {{-- Validation Errors --}}
                    <x-auth-validation-errors class="mb-4" :errors="$errors" />

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        {{-- Email Address --}}
                        <div>
                            <x-label for="email" :value="__('Email')" />

                            <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-button>
                                {{ __('Email Password Reset Link') }}
                            </x-button>
                        </div>
                    </form>
                </div>


            </div>
        </div>
    </div>
@endsection
