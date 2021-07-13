{{-- 編輯個人資料 --}}
@extends('layouts.app')

@section('title', '編輯 ' . $user->name . ' 的個人資料')

@section('content')
    <div class="container mx-auto max-w-7xl py-6">
        <div class="flex justify-center items-center px-4 xl:px-0">

            <div class="w-full md:w-2/3 xl:w-2/5 flex flex-col justify-center items-center">
                {{-- Title --}}
                <div class="fill-current text-gray-700 text-2xl dark:text-white">
                    <i class="bi bi-person-lines-fill"></i><span class="ml-4">編輯個人資料</span>
                </div>

                {{-- Form --}}
                <x-card class="w-full mt-4 overflow-hidden">

                    <div class="w-full flex flex-col justify-center items-center mb-4">
                        <div>
                            <img class="rounded-full h-36 w-36" src="{{ $user->gravatar('500') }}" alt="{{ $user->name }}">
                        </div>

                        <div class="flex mt-4 dark:text-white">
                            <span class="mr-2">會員大頭貼由</span>
                            <a href="https://zh-tw.gravatar.com/" target="_blank" rel="nofollow noopener noreferrer"
                            class="text-gray-400 hover:text-gray-700 dark:hover:text-white">Gravatar</a>
                            <span class="ml-2">技術提供</span>
                        </div>
                    </div>

                    {{-- Validation Errors --}}
                    <x-auth-validation-errors class="mb-4" :errors="$errors" />

                    <form method="POST" action="{{ route('users.update', ['user' => $user->id]) }}">
                        @method('PUT')
                        @csrf

                        {{-- Email Address --}}
                        <div class="mt-5">
                            <x-floating-label-input
                                :type="'text'"
                                :name="'email'"
                                :placeholder="'Email address'"
                                :value="old('email', $user->email)"
                                required
                                readonly
                            ></x-floating-label-input>
                        </div>

                        {{-- Name --}}
                        <div class="mt-10">
                            <x-floating-label-input
                                :type="'text'"
                                :name="'name'"
                                :placeholder="'Name'"
                                :value="old('name', $user->name)"
                                required
                                autofocus
                            ></x-floating-label-input>
                        </div>

                        {{-- Introduction --}}
                        <div class="mt-5">
                            <label for="introduction" class="hidden">Introduction</label>

                            <textarea
                                name="introduction"
                                placeholder="Introduction"
                                rows="5"
                                class="form-textarea w-full rounded-md shadow-sm border border-gray-300
                                focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50
                                dark:bg-gray-500 dark:text-white dark:placeholder-white"
                            >{{ old('introduction', $user->introduction) }}</textarea>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            {{-- Save Button --}}
                            <x-button>
                                <i class="bi bi-save2-fill"></i><span class="ml-2">儲存</span>
                            </x-button>
                        </div>
                    </form>
                </x-card>
            </div>

        </div>
    </div>
@endsection
