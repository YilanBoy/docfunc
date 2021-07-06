{{-- 文章列表 --}}
@extends('layouts.app')

@section('title', isset($category) ? $category->name : '所有文章')

@section('content')
    <div class="container mx-auto max-w-7xl mt-6">

        {{-- 文章訊息區塊 --}}
        <div class="md:hidden px-4 mb-6">
            {{-- 分類 --}}
            @if (isset($category))
            <div class="flex justify-center items-center text-blue-700 border-blue-700 rounded-xl border-2
                bg-gradient-to-br from-blue-100 to-blue-300 px-4 py-2">
                    <span class="font-bold">{{ $category->name }}：</span>
                    <span>{{ $category->description }}</span>
                </div>
            @endif

            {{-- 標籤 --}}
            @if (isset($tag))
                <div class="flex justify-center items-center text-green-700 border-green-700 rounded-xl border-2
                bg-gradient-to-br from-green-100 to-green-300 px-4 py-2">
                    <span class="font-bold">{{ $tag->name }}</>
                </div>
            @endif
        </div>

        <div class="flex flex-col space-y-6 xl:space-y-0 xl:flex-row justify-center px-4 xl:px-0">
            {{-- 文章列表 --}}
            @livewire('posts', [
                'currentUrl' => url()->current(),
                'category' => isset($category) ? $category : null,
                'tag' => isset($tag) ? $tag : null,
            ])

            {{-- 文章列表側邊欄區塊 --}}
            <div class="w-full xl:w-80 space-y-6">
                {{-- 介紹 --}}
                <div class="text-gray-600 bg-white shadow-md p-5 rounded-xl ring-1 ring-black ring-opacity-20">
                    <h3 class="font-semibold text-lg text-center mb-3">歡迎來到 <span class="font-mono">{{ config('app.name') }}</span>！</h3>
                    <div class="mt-2 pt-3 border-gray-600 border-t-2">
                        <span>用部落格來紀錄自己生活上大大小小的事情吧！此部落格使用 Laravel 與 Tailwind CSS 開發</span>
                    </div>
                    <a href="{{ route('posts.create') }}"
                    class="block w-full text-white font-bold bg-green-600 hover:bg-green-800 active:bg-green-600 rounded-md text-center py-2 mt-7 shadow-lg">
                        <i class="bi bi-pencil"></i><span class="ml-2">新增文章</span>
                    </a>
                </div>

                {{-- 熱門標籤 --}}
                @if ($popularTags->count())
                <div class="text-gray-600 bg-white shadow-md p-5 rounded-xl ring-1 ring-black ring-opacity-20">
                    <h3 class="font-semibold text-lg text-center mb-3"><i class="bi bi-tags-fill"></i><span class="ml-2">熱門標籤</span></h3>
                    <div class="mt-2 pt-3 border-gray-600 border-t-2 flex flex-wrap">
                        @foreach ($popularTags as $popularTag)
                            <a href="{{ route('tags.show', ['tag' => $popularTag->id]) }}"
                            class="text-xs inline-flex items-center font-bold leading-sm uppercase px-3 py-1 m-1
                            bg-green-200 hover:bg-green-400 active:bg-green-200 text-green-700 rounded-full shadow-lg ring-1 ring-green-700">
                                {{ $popularTag->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- 學習資源推薦 --}}
                @if ($links->count())
                    <div class="text-gray-600 bg-white shadow-md p-5 rounded-xl ring-1 ring-black ring-opacity-20">
                        <h3 class="font-semibold text-lg text-center mb-3"><i class="bi bi-journal-code"></i><span class="ml-2">學習資源推薦</span></h3>
                        <div class="mt-2 pt-3 border-gray-600 border-t-2 flex flex-col">
                            @foreach ($links as $link)
                                <a href="{{ $link->link }}" target="_blank" rel="nofollow noopener noreferrer"
                                class="block text-black rounded-md p-2 bg-white hover:bg-gray-200">
                                    {{ $link->title }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>
@endsection
