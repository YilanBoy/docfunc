{{-- 文章列表 --}}
@extends('layouts.app')

@section('title', isset($category) ? $category->name : '所有文章')

@section('content')
    <main class="container mx-auto max-w-7xl flex flex-col xl:flex-row justify-center px-4 xl:px-0">
        {{-- 文章列表 --}}
        <div class="w-full xl:w-2/3 px-2 xl:px-0 mr-0 xl:mr-6">
            @livewire('posts', [
                'currentUrl' => url()->current(),
                'category' => isset($category) ? $category : null,
                'tag' => isset($tag) ? $tag : null,
            ])
        </div>

        {{-- 文章列表側邊欄區塊 --}}
        <div class="w-full xl:w-80 space-y-6 mb-6">
            {{-- 介紹 --}}
            <div class="bg-white border-blue rounded-xl ring-1 ring-black ring-opacity-20">
                <div class="text-gray-600 p-5">
                    <h3 class="font-semibold text-lg text-center mb-3">歡迎來到 {{ config('app.name') }}！</h3>
                    <p class="mt-2 pt-3 border-gray-600 border-t-2">
                        用部落格來紀錄自己生活上大大小小的事情吧！此部落格使用 Laravel 與 TailwindCSS 開發
                    </p>
                    <a href="{{ route('posts.create') }}"
                    class="block w-full text-white bg-green-600 hover:bg-green-800 active:bg-green-600 rounded-md text-center py-2 mt-7 shadow-lg">
                        <i class="bi bi-pencil"></i><span class="ml-2">新增文章</span>
                    </a>
                </div>
            </div>

            {{-- 熱門標籤 --}}
            @if ($popularTags->count())
            <div class="bg-white border-blue rounded-xl ring-1 ring-black ring-opacity-20">
                <div class="text-gray-600 p-5">
                    <h3 class="font-semibold text-lg text-center mb-3"><i class="bi bi-tags-fill"></i> 熱門標籤</h3>
                    <div class="mt-2 pt-3 border-gray-600 border-t-2 flex flex-wrap">
                        @foreach ($popularTags as $popularTag)
                            <a href="{{ route('tags.show', ['tag' => $popularTag->id]) }}"
                            class="block text-sm text-white rounded-2xl py-1 px-3 m-1 bg-blue-500 hover:bg-blue-800 active:bg-blue-500 shadow-lg">
                                {{ $popularTag->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            {{-- 學習資源推薦 --}}
            @if ($links->count())
                <div class="bg-white border-blue rounded-xl ring-1 ring-black ring-opacity-20">
                    <div class="text-gray-600 p-5">
                        <h3 class="font-semibold text-lg text-center mb-3"><i class="bi bi-journal-code"></i> 學習資源推薦</h3>
                        <div class="mt-2 pt-3 border-gray-600 border-t-2 flex flex-col">
                            @foreach ($links as $link)
                                <a href="{{ $link->link }}" target="_blank" rel="nofollow noopener noreferrer"
                                class="block text-base text-black rounded-md p-2 bg-white hover:bg-gray-200">
                                    {{ $link->title }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </main>
@endsection
