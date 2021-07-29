{{-- 個人頁面 --}}
@extends('layouts.app')

@section('title', $user->name . ' 的個人頁面')

@section('content')
    <div class="container mx-auto max-w-7xl">
        <div class="min-h-screen space-y-6 xl:space-y-0 px-4 xl:px-0 mt-6
        flex flex-col xl:flex-row justify-center items-start">

            <div class="w-full xl:w-80 xl:mr-6
            flex flex-col md:flex-row xl:flex-col justify-center items-center md:items-start xl:items-center">
                {{-- 會員資訊 --}}
                <x-card class="w-full lg:w-80 flex flex-col justify-center items-center md:mr-6 xl:mr-0
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

                {{-- 會員個人簡介 --}}
                <x-card class="w-full lg:w-80 flex flex-col justify-center items-center text-black mt-6 md:mt-0 xl:mt-6
                dark:text-white">

                    <span class="w-full flex justify-center items-center text-2xl
                    font-semibold mb-5 pb-5 border-b-2 border-black
                    dark:text-white dark:border-white">
                        個人簡介
                    </span>

                    @if ($user->introduction)
                        <span class="w-full flex justify-start items-center">{!! nl2br(e($user->introduction)) !!}</span>
                    @else
                        <span class="w-full flex justify-center items-center">目前尚無個人簡介～</span>
                    @endif
                </x-card>
            </div>


            <div
                x-data="{ tab : 'posts' }"
                class="w-full xl:w-2/3 mr-0 xl:mr-6 space-y-6"
            >
                <nav class="flex font-semibold">
                    <a
                        x-on:click.prevent="tab = 'posts'"
                        href="#"
                        :class="{
                            'border-blue-500 text-gray-700 dark:text-white': tab === 'posts',
                            'border-gray-400 text-gray-400': tab === 'replies'
                        }"
                        class="block transition duration-150 ease-in hover:border-blue-500 hover:text-gray-700 border-b-4 px-2 sm:px-7 py-2
                        dark:hover:text-white"
                    >
                        <span>發布文章</span>
                    </a>
                    <a
                        x-on:click.prevent="tab = 'replies'"
                        href="#"
                        :class="{
                            'border-blue-500 text-gray-700 dark:text-white': tab === 'replies',
                            'border-gray-400 text-gray-400': tab === 'posts'
                        }"
                        class="block transition duration-150 ease-in hover:border-blue-500 hover:text-gray-700 border-b-4 px-2 sm:px-7 py-2
                        dark:hover:text-white"
                    >
                        <span>回覆紀錄</span>
                    </a>
                </nav>

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
