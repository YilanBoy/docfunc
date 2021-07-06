{{-- 個人頁面 --}}
@extends('layouts.app')

@section('title', $user->name . ' 的個人頁面')

@section('content')
    <div class="flex-grow container mx-auto max-w-7xl mt-6">
        <div class="flex flex-col xl:flex-row justify-center space-y-6 xl:space-y-0 px-4 xl:px-0">

            <div class="w-full sm:w-80 mx-auto xl:mr-6">
                <div class="flex flex-col justify-center items-center text-gray-600 bg-white shadow-md p-5
                rounded-xl ring-1 ring-black ring-opacity-20">
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

                <div class="flex flex-col justify-center items-center text-gray-600 bg-white shadow-md p-5
                rounded-xl ring-1 ring-black ring-opacity-20 mt-6">

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

            <div class="w-full xl:w-2/3 mr-0 xl:mr-6 space-y-6">
                <nav class="flex items-center font-semibold">
                    <a
                        href="{{ route('users.show', ['user' => $user->id]) }}"
                        class="block transition duration-150 ease-in hover:border-blue-500 hover:text-gray-700
                        border-b-4 px-2 sm:px-7 py-2
                        @if (!str_contains(request()->fullUrl(), 'replies')) border-blue-500 text-gray-700 @else text-gray-400 @endif"
                    >
                        <span>發布文章</span>
                    </a>
                    <a
                        href="{{ route('users.show', ['user' => $user->id, 'tab' => 'replies']) }}"
                        class="block transition duration-150 ease-in hover:border-blue-500 hover:text-gray-700
                        border-b-4 px-2 sm:px-7 py-2
                        @if (str_contains(request()->fullUrl(), 'replies')) border-blue-500 text-gray-700 @else text-gray-400 @endif"
                    >
                        <span>回覆紀錄</span>
                    </a>
                </nav>

                <div class="space-y-6">
                    @if (str_contains(request()->getUri(), 'replies'))
                        @include('users.replies', ['replies' => $replies])
                    @else
                        @include('users.posts', ['posts' => $posts])
                    @endif
                </div>
            </div>

        </div>
    </div>
@endsection
