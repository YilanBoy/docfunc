{{-- 文章列表 --}}
@extends('layouts.app')

@section('title', isset($category) ? $category->name : '所有文章')

@section('content')
    <div class="container mx-auto min-h-screen max-w-7xl mt-6">

        {{-- 文章訊息區塊 --}}
        <div class="md:hidden px-4 mb-6">
            {{-- 分類 --}}
            @if (isset($category))
            <div class="flex justify-center items-center text-blue-700 border-blue-700 rounded-xl border
                bg-gradient-to-br from-blue-100 to-blue-300 px-4 py-2">
                    <span class="font-bold">{{ $category->name }}：</span>
                    <span>{{ $category->description }}</span>
                </div>
            @endif

            {{-- 標籤 --}}
            @if (isset($tag))
                <div class="flex justify-center items-center text-green-700 border-green-700 rounded-xl border
                bg-gradient-to-br from-green-100 to-green-300 px-4 py-2">
                    <span class="font-bold">{{ $tag->name }}</span>
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
                <x-card class="text-black dark:text-white">
                    <h3 class="font-semibold text-lg text-center border-black border-b-2 pb-3 mb-3
                    dark:border-white">
                        歡迎來到 <span class="font-mono">{{ config('app.name') }}</span>！
                    </h3>
                    <div>
                        <div>用部落格紀錄生活上的大小事</div>
                        <div class="mt-1">此部落格使用 Laravel、Alpine.js 與 Tailwind CSS 開發</div>
                    </div>
                    <div class="flex justify-center items-center mt-7">
                        <a href="{{ route('posts.create') }}"
                        class="group relative h-12 w-64 inline-flex rounded-lg border border-green-600 focus:outline-none">
                            <span class="absolute inset-0 inline-flex items-center justify-center self-stretch py-2 text-white text-center font-medium bg-green-600
                            rounded-lg ring-1 ring-green-600 ring-offset-1 ring-offset-green-600 transform transition-transform
                            group-hover:-translate-y-2 group-hover:-translate-x-2 group-focus:-translate-y-2 group-focus:-translate-x-2
                            group-active:-translate-y-0 group-active:-translate-x-0">
                                <i class="bi bi-pencil-fill"></i><span class="ml-2">新增文章</span>
                            </span>
                        </a>
                    </div>
                </x-card>

                {{-- 熱門標籤 --}}
                @if ($popularTags->count())
                    <x-card class="text-black dark:text-white">
                        <h3 class="font-semibold text-lg text-center border-black border-b-2 pb-3 mb-3
                        dark:border-white">
                            <i class="bi bi-tags-fill"></i><span class="ml-2">熱門標籤</span>
                        </h3>
                        <div class="flex flex-wrap">
                            @foreach ($popularTags as $popularTag)
                                <a href="{{ route('tags.show', ['tag' => $popularTag->id]) }}"
                                class="text-xs inline-flex items-center font-bold leading-sm uppercase px-3 py-1 m-1
                                bg-green-200 hover:bg-green-400 active:bg-green-200 text-green-700 rounded-full shadow-lg ring-1 ring-green-700">
                                    {{ $popularTag->name }}
                                </a>
                            @endforeach
                        </div>
                    </x-card>
                @endif

                {{-- 學習資源推薦 --}}
                @if ($links->count())
                    <x-card class="text-black dark:text-white">
                        <h3 class="font-semibold text-lg text-center border-black border-b-2 pb-3 mb-3
                        dark:border-white">
                            <i class="bi bi-file-earmark-code-fill"></i><span class="ml-2">學習資源推薦</span>
                        </h3>
                        <div class="flex flex-col">
                            @foreach ($links as $link)
                                <a href="{{ $link->link }}" target="_blank" rel="nofollow noopener noreferrer"
                                class="block text-black rounded-md p-2 hover:bg-gray-200
                                dark:text-white dark:hover:bg-gray-500">
                                    {{ $link->title }}
                                </a>
                            @endforeach
                        </div>
                    </x-card>
                @endif
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('cardLink', () => ({
                // 文章連結
                postCardLink(event, refs) {
                    let ignores = ['a'];
                    let targetTagName = event.target.tagName.toLowerCase();

                    if (!ignores.includes(targetTagName)) {
                        refs.postLink.click();
                    }
                }
            }));
        });
    </script>
@endsection
