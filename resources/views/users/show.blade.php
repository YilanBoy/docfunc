{{-- 個人頁面 --}}
@extends('layouts.app')

@section('title', $user->name . ' 的個人頁面')

@section('content')
    <div class="flex-grow container mx-auto max-w-7xl mt-6">
        <div class="flex flex-col xl:flex-row justify-center items-start space-y-6 xl:space-y-0 px-4 xl:px-0">

            <div class="w-full xl:w-80
            flex flex-col md:flex-row xl:flex-col justify-center items-center md:items-start xl:items-center
            xl:mr-6">
                <div class="w-full lg:w-80 flex flex-col justify-center items-center
                text-gray-600 bg-white shadow-md rounded-xl ring-1 ring-black ring-opacity-20
                md:mr-6 xl:mr-0 p-5">
                    {{-- 大頭貼 --}}
                    <div>
                        <img class="rounded-full h-36 w-36" src="{{ $user->gravatar('500') }}" alt="{{ $user->name }}"  width="200">
                    </div>

                    {{-- 會員名稱 --}}
                    <span class="w-full flex justify-center items-center text-3xl text-black
                    font-semibold my-5 pb-5 border-b-2 border-gray-700">{{ $user->name }}</span>

                    {{-- 資訊 --}}
                    <div class="w-full flex flex-col justify-start items-start">
                        <span class="text-lg">撰寫文章</span>
                        <span class="text-xl text-black font-semibold mt-2">{{ $user->posts->count() }} 篇</span>

                        <span class="text-lg mt-4">文章留言</span>
                        <span class="text-xl text-black font-semibold mt-2">{{ $user->replies->count() }} 次</span>

                        <span class="mt-4 text-xs">註冊於 {{ $user->created_at->format('Y / m / d') . '（' . $user->created_at->diffForHumans() . '）' }}</span>
                    </div>
                </div>

                <div class="w-full lg:w-80 flex flex-col justify-center items-center
                text-gray-600 bg-white shadow-md rounded-xl ring-1 ring-black ring-opacity-20
                mt-6 md:mt-0 xl:mt-6 p-5">

                    <span class="w-full flex justify-center items-center text-2xl text-black
                    font-semibold mb-5 pb-5 border-b-2 border-gray-700">
                        個人簡介
                    </span>

                    @if ($user->introduction)
                        <span class="w-full flex justify-start items-center">{!! nl2br(e($user->introduction)) !!}</span>
                    @else
                        <span class="w-full flex justify-center items-center">目前尚無個人簡介～</span>
                    @endif
                </div>
            </div>


            <div
                x-data="{ tab: 'posts' }"
                class="w-full xl:w-2/3 mr-0 xl:mr-6 space-y-6"
            >
                <nav class="flex font-semibold">
                    <button
                        x-on:click="tab = 'posts'"
                        :class="{
                            'border-blue-500 text-gray-700': tab === 'posts',
                            'text-gray-400': tab === 'replies'
                        }"
                        class="block transition duration-150 ease-in hover:border-blue-500 hover:text-gray-700
                        border-b-4 px-2 sm:px-7 py-2"
                    >
                        <span>發布文章</span>
                    </button>
                    <button
                        x-on:click="tab = 'replies'"
                        :class="{
                            'border-blue-500 text-gray-700': tab === 'replies',
                            'text-gray-400': tab === 'posts'
                        }"
                        class="block transition duration-150 ease-in hover:border-blue-500 hover:text-gray-700
                        border-b-4 px-2 sm:px-7 py-2"
                    >
                        <span>回覆紀錄</span>
                    </button>
                </nav>

                {{-- 會員文章 --}}
                <div
                    x-cloak
                    x-show.transition.in.duration.300ms="tab === 'posts'"
                >
                    @livewire('user.posts', [
                        'user' => $user,
                    ])
                </div>

                {{-- 會員回覆 --}}
                <div
                    x-cloak
                    x-show.transition.in.duration.300ms="tab === 'replies'"
                >
                    @livewire('user.replies', [
                        'user' => $user,
                    ])
                </div>
            </div>
        </div>
    </div>
@endsection
