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

                <x-card class="w-full sm:max-w-md mt-4 overflow-hidden">

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
                        <div class="relative mt-10">
                            <input
                                id="email"
                                name="email"
                                type="text"
                                placeholder="Email Address"
                                required
                                autofocus
                                class="peer h-10 w-full border-b-2 border-gray-300 text-gray-900
                                placeholder-transparent focus:outline-none focus:border-blue-600"
                            >

                            <label
                                for="email"
                                class="absolute left-0 -top-3.5 text-gray-600 text-sm
                                transition-all peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400 peer-placeholder-shown:top-2
                                peer-focus:-top-3.5 peer-focus:text-gray-600 peer-focus:text-sm"
                            >
                                Email address
                            </label>
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
