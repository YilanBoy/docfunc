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
                        <div class="relative mt-5">
                            <input
                                id="email"
                                name="email"
                                type="text"
                                placeholder="Email Address"
                                value="{{ old('email', $user->email) }}"
                                required
                                readonly
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

                        {{-- Name --}}
                        <div class="relative mt-10">
                            <input
                                id="name"
                                name="name"
                                type="text"
                                placeholder="Name"
                                value="{{ old('name', $user->name) }}"
                                required
                                autofocus
                                class="peer h-10 w-full border-b-2 border-gray-300 text-gray-900
                                placeholder-transparent focus:outline-none focus:border-blue-600"
                            >

                            <label
                                for="name"
                                class="absolute left-0 -top-3.5 text-gray-600 text-sm
                                transition-all peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400 peer-placeholder-shown:top-2
                                peer-focus:-top-3.5 peer-focus:text-gray-600 peer-focus:text-sm"
                            >
                                Name
                            </label>
                        </div>

                        {{-- Introduction --}}
                        <div class="mt-5">
                            <label for="introduction" class="hidden">Introduction</label>

                            <textarea
                                id="introduction"
                                name="introduction"
                                placeholder="Introduction"
                                class="outline-none p-2 h-32 w-full rounded-md shadow-sm border border-gray-300
                                focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            >
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
