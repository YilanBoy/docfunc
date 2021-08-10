{{-- 個人頁面 --}}
@extends('layouts.app')

@section('title', $user->name . ' 的個人頁面')

@section('content')
    <div class="container mx-auto max-w-7xl">
        <div class="min-h-screen flex flex-col md:flex-row justify-center items-start px-4 xl:px-0 mt-6">

            {{-- 會員資訊 --}}
            <x-card class="w-full md:w-80 flex flex-col justify-center items-center mr-0 xl:mr-6
            dark:text-white">
                {{-- 大頭貼 --}}
                <div>
                    <img class="rounded-full h-36 w-36" src="{{ $user->gravatar('500') }}" alt="{{ $user->name }}"  width="200">
                </div>

                {{-- 會員名稱 --}}
                <span class="w-full flex justify-center items-center text-3xl text-black font-semibold my-5 pb-5 border-b-2 border-black
                dark:text-white dark:border-white">{{ $user->name }}</span>

                {{-- 資訊 --}}
                <div class="w-full flex flex-col justify-start items-start text-black
                dark:text-white">
                    <span class="text-lg">撰寫文章</span>
                    <span class="text-xl font-semibold mt-2">{{ $user->posts->count() }} 篇</span>

                    <span class="text-lg mt-4">文章留言</span>
                    <span class="text-xl font-semibold mt-2">{{ $user->replies->count() }} 次</span>

                    <span class="mt-4 text-xs">註冊於 {{ $user->created_at->format('Y / m / d') . '（' . $user->created_at->diffForHumans() . '）' }}</span>
                </div>
            </x-card>

            <div
                x-data="{ tab : 'information' }"
                class="w-full xl:w-2/3 space-y-6"
            >
                <nav class="flex font-semibold">

                    <div class="group">
                        <a
                            x-on:click.prevent="tab = 'information'"
                            href="#"
                            :class="{
                                'text-gray-700 dark:text-white': tab === 'information',
                                'text-gray-400 hover:text-gray-700 dark:hover:text-white': tab !== 'information'
                            }"
                            class="block transition duration-300 ease-in px-2 sm:px-7 py-2"
                        >
                            <span>會員資訊</span>
                        </a>
                        <div class="bg-gray-200 dark:bg-gray-600">
                            <div
                                :class="{
                                    'w-full': tab === 'information',
                                    'w-0 group-hover:w-full': tab !== 'information'
                                }"
                                class="h-1 bg-blue-500 transition-all duration-300"
                            ></div>
                        </div>
                    </div>

                    <div class="group">
                        <a
                            x-on:click.prevent="tab = 'posts'"
                            href="#"
                            :class="{
                                'text-gray-700 dark:text-white': tab === 'posts',
                                'text-gray-400 hover:text-gray-700 dark:hover:text-white': tab !== 'posts'
                            }"
                            class="block transition duration-300 ease-in px-2 sm:px-7 py-2"
                        >
                            <span>發布文章</span>
                        </a>
                        <div class="bg-gray-200 dark:bg-gray-600">
                            <div
                                :class="{
                                    'w-full': tab === 'posts',
                                    'w-0 group-hover:w-full': tab !== 'posts'
                                }"
                                class="h-1 bg-blue-500 transition-all duration-300"
                            ></div>
                        </div>
                    </div>

                    <div class="group">
                        <a
                            x-on:click.prevent="tab = 'replies'"
                            href="#"
                            :class="{
                                'text-gray-700 dark:text-white': tab === 'replies',
                                'text-gray-400 hover:text-gray-700 dark:hover:text-white': tab !== 'replies'
                            }"
                            class="block transition duration-300 ease-in px-2 sm:px-7 py-2"
                        >
                            <span>回覆紀錄</span>
                        </a>
                        <div class="bg-gray-200 dark:bg-gray-600">
                            <div
                                :class="{
                                    'w-full': tab === 'replies',
                                    'w-0 group-hover:w-full': tab !== 'replies'
                                }"
                                class="h-1 bg-blue-500 transition-all duration-300"
                            ></div>
                        </div>
                    </div>

                </nav>

                {{-- 會員資訊 --}}
                <div
                    x-cloak
                    x-show="tab === 'information'"
                    x-transition:enter.duration.300ms
                >
                    @include('users.information')
                </div>

                {{-- 會員文章 --}}
                <div
                    x-cloak
                    x-show="tab === 'posts'"
                    x-transition:enter.duration.300ms
                >
                    @livewire('user.posts', [
                        'user' => $user,
                    ])
                </div>

                {{-- 會員回覆 --}}
                <div
                    x-cloak
                    x-show="tab === 'replies'"
                    x-transition:enter.duration.300ms
                >
                    @livewire('user.replies', [
                        'user' => $user,
                    ])
                </div>
            </div>
        </div>
    </div>
@endsection
