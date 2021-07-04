@extends('layouts.app')

@section('title', '重設密碼')

@section('content')
    <div class="container mx-auto max-w-7xl py-6">
        <div class="flex justify-center items-center px-4 xl:px-0">
            <div class="w-full lg:w-1/3 flex flex-col sm:justify-center items-center bg-gray-100 pb-12">

                {{-- Logo --}}
                <div class="fill-current text-gray-700 text-2xl">
                    <i class="bi bi-question-circle"></i><span class="ml-4">重設密碼</span>
                </div>

                <div class="w-full sm:max-w-md mt-4 px-6 py-4 bg-white shadow-md overflow-hidden rounded-xl ring-1 ring-black ring-opacity-20">
                    {{-- Validation Errors --}}
                    <x-auth-validation-errors class="mb-4" :errors="$errors" />

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        {{-- Password Reset Token --}}
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                        {{-- Email Address --}}
                        <div>
                            <x-label for="email" :value="__('Email')" />

                            <x-input id="email" class="block mt-1 w-full read-only:bg-gray-200" type="email" name="email" :value="$request->email ?? old('email')" required readonly />
                        </div>

                        {{-- Password --}}
                        <div class="mt-4">
                            <x-label for="password" :value="__('Password') . '（最少 8 個字元）'" />

                            <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autofocus />
                        </div>

                        {{-- Confirm Password --}}
                        <div class="mt-4">
                            <x-label for="password_confirmation" :value="__('Confirm Password')" />

                            <x-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-button>
                                {{ __('Reset Password') }}
                            </x-button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection
