{{-- 編輯個人資料 --}}
@extends('layouts.app')

@section('title', '編輯 ' . $user->name . ' 的個人資料')

@section('content')
    <div class="container mx-auto max-w-7xl py-6">
        <div class="flex justify-center items-center px-4 xl:px-0">

            <div class="w-full md:w-2/3 xl:w-2/5 flex flex-col justify-center items-center bg-gray-100">
                {{-- Title --}}
                <div class="fill-current text-gray-700 text-2xl">
                    <i class="bi bi-person-lines-fill"></i><span class="ml-4">編輯個人資料</span>
                </div>

                {{-- Form --}}
                <div class="w-full mt-4 px-6 py-4 bg-white shadow-md overflow-hidden rounded-xl ring-1 ring-black ring-opacity-20">

                    <div class="w-full flex flex-col justify-center items-center mb-4">
                        <div>
                            <img class="rounded-full h-36 w-36" src="{{ $user->gravatar('500') }}" alt="{{ $user->name }}">
                        </div>

                        <div class="flex mt-4">
                            <span class="mr-2">會員大頭貼由</span>
                            <a href="https://zh-tw.gravatar.com/" target="_blank" rel="nofollow noopener noreferrer"
                            class="text-gray-400 hover:text-gray-700">Gravatar</a>
                            <span class="ml-2">技術提供</span>
                        </div>
                    </div>

                    {{-- Validation Errors --}}
                    <x-auth-validation-errors class="mb-4" :errors="$errors" />

                    <form method="POST" action="{{ route('users.update', ['user' => $user->id]) }}">
                        @method('PUT')
                        @csrf

                        {{-- Email Address --}}
                        <div>
                            <x-label for="email" :value="__('Email')" />

                            <x-input id="email" class="block mt-1 w-full read-only:bg-gray-200" type="email" name="email" :value="old('email', $user->email)" required readonly />
                        </div>

                        <div class="mt-4">
                            <x-label for="name" :value="__('Name') . '（請使用英文、數字、橫槓和底線）'" />

                            <x-input id="name" :value="old('name', $user->name)" class="block mt-1 w-full" type="text" name="name" required autofocus />
                        </div>

                        {{-- Password --}}
                        <div class="mt-4">
                            <x-label for="introduction" :value="'個人簡介'" />

                            <textarea name="introduction" id="introduction"
                            class="block mt-1 h-32 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                {{ old('introduction', $user->introduction) }}
                            </textarea>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            {{-- Save Button --}}
                            <x-button>
                                <i class="bi bi-save2-fill"></i><span class="ml-2">儲存</span>
                            </x-button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
@endsection
