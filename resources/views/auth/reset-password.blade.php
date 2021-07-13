@extends('layouts.app')

@section('title', '重設密碼')

@section('content')
    <div class="container mx-auto max-w-7xl py-6">
        <div class="flex justify-center items-center px-4 xl:px-0">

            <div class="w-full flex flex-col justify-center items-center">

                {{-- Title --}}
                <div class="fill-current text-gray-700 text-2xl dark:text-white">
                    <i class="bi bi-question-circle"></i><span class="ml-4">重設密碼</span>
                </div>

                <x-card class="w-full sm:max-w-md mt-4 overflow-hidden">
                    {{-- Validation Errors --}}
                    <x-auth-validation-errors class="mb-4" :errors="$errors" />

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        {{-- Password Reset Token --}}
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                        {{-- Email Address --}}
                        <div class="mt-5">
                            <x-floating-label-input
                                :type="'text'"
                                :name="'email'"
                                :placeholder="'Email address'"
                                :value="$request->email ?? old('email')"
                                required
                                readonly
                            ></x-floating-label-input>
                        </div>

                        {{-- Password --}}
                        <div class="mt-10">
                            <x-floating-label-input
                                :type="'password'"
                                :name="'password'"
                                :placeholder="'Password'"
                                required
                                autofocus
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


                        <div class="flex items-center justify-end mt-4">
                            <x-button>
                                {{ __('Reset Password') }}
                            </x-button>
                        </div>
                    </form>
                </x-card>

            </div>
        </div>
    </div>
@endsection
