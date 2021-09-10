{{-- 文章內容 --}}
@extends('layouts.app')

@section('title', $post->title)

@section('description', $post->excerpt)

@section('css')
    <link href="{{ asset('css/content-styles.css') }}" rel="stylesheet">
    <link href="{{ asset('css/missing-content-styles.css') }}" rel="stylesheet">
    <link href="{{ asset('prism/prism.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="relative mt-6">
        {{-- 置頂按鈕 --}}
        <button id="scroll-to-top-btn" title="Go to top"
        class="fixed z-10 bottom-7 right-7 hidden justify-center h-16 w-16 text-gray-50 font-bold bg-blue-600 rounded-full
        transform hover:scale-125 transition duration-150 ease-in shadow-md hover:shadow-xl">
            <span class="animate-bounce text-3xl mt-4">
                <i class="bi bi-arrow-up"></i>
            </span>
        </button>

        <div class="container mx-auto max-w-7xl">
            <div class="min-h-screen flex flex-col justify-start items-center px-4 xl:px-0">

                <x-card class="relative w-full xl:w-2/3">

                    {{-- 文章選單-桌面裝置 --}}
                    <div
                        x-data="{}"
                        class="hidden xl:block absolute top-0 left-103/100 w-16 h-full"
                    >
                        <div class="sticky top-7 flex flex-col justify-center items-center">
                            @if(auth()->id() === $post->user_id)
                                {{-- 編輯文章 --}}
                                <a
                                    href="{{ route('posts.edit', ['post' => $post->id]) }}"
                                    class="group relative w-16 h-16 inline-flex rounded-xl border border-green-600"
                                >
                                    <span class="absolute inset-0 inline-flex items-center justify-center self-stretch text-2xl text-gray-50 text-center font-medium bg-green-600
                                    rounded-xl ring-1 ring-green-600 ring-offset-1 ring-offset-green-600 transform transition-transform
                                    group-hover:-translate-y-2 group-hover:-translate-x-2 group-active:-translate-y-0 group-active:-translate-x-0">
                                        <span class="transform group-hover:scale-125 group-hover:-rotate-45 transition duration-150 ease-in">
                                            <i class="bi bi-pencil-fill"></i>
                                        </span>
                                    </span>
                                </a>

                                <form id="soft-delete-post" action="{{ route('posts.softDelete', ['post' => $post->id]) }}" method="POST"
                                class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>

                                {{-- 軟刪除 --}}
                                <button
                                    x-on:click="
                                        if (confirm('您確定標記此文章為刪除狀態嗎？（時間內還可以還原）')) {
                                            document.getElementById('soft-delete-post').submit()
                                        }
                                    "
                                    type="button"
                                    class="group relative w-16 h-16 inline-flex rounded-xl border border-yellow-600 focus:outline-none mt-4"
                                >
                                    <span class="absolute inset-0 inline-flex items-center justify-center self-stretch text-2xl text-gray-50 text-center font-medium bg-yellow-600
                                    rounded-xl ring-1 ring-yellow-600 ring-offset-1 ring-offset-yellow-600 transform transition-transform
                                    group-hover:-translate-y-2 group-hover:-translate-x-2 group-active:-translate-y-0 group-active:-translate-x-0">
                                        <i class="bi bi-file-earmark-x-fill"></i>
                                    </span>
                                </button>
                            @endif
                        </div>
                    </div>

                    <div class="flex justify-between">
                        {{-- 文章標題 --}}
                        <h1 class="flex-grow text-3xl font-bold dark:text-gray-50">{{ $post->title }}</h1>

                        {{-- 文章選單-行動裝置 --}}
                        @if(auth()->id() === $post->user_id)
                            <div
                                x-data="{ editMenuIsOpen: false }"
                                class="relative xl:hidden"
                            >
                                <div>
                                    <button
                                        x-on:click="editMenuIsOpen = ! editMenuIsOpen"
                                        x-on:click.outside="editMenuIsOpen = false"
                                        x-on:keydown.escape.window="editMenuIsOpen = false"
                                        type="button"
                                        class="text-2xl text-gray-400 hover:text-gray-700 focus:text-gray-700
                                        dark:hover:text-gray-50 dark:focus:text-gray-50"
                                        aria-expanded="false" aria-haspopup="true"
                                    >
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                </div>

                                <div
                                    x-cloak
                                    x-show="editMenuIsOpen"
                                    x-transition.origin.top.right
                                    class="absolute right-0 z-10 p-2 mt-2 w-48 rounded-md shadow-lg bg-gray-50 text-gray-700 ring-1 ring-black ring-opacity-20
                                    dark:bg-gray-500 dark:text-gray-50"
                                    role="menu" aria-orientation="vertical" tabindex="-1"
                                >
                                    {{-- 編輯文章 --}}
                                    <a
                                        href="{{ route('posts.edit', ['post' => $post->id]) }}"
                                        role="menuitem" tabindex="-1"
                                        class="block px-4 py-2 rounded-md hover:bg-gray-200 active:bg-gray-100
                                        dark:hover:bg-gray-400"
                                    >
                                        <i class="bi bi-pencil-fill"></i><span class="ml-2">編輯</span>
                                    </a>

                                    {{-- 軟刪除 --}}
                                    <button
                                        x-on:click="
                                            if (confirm('您確定標記此文章為刪除狀態嗎？（時間內還可以還原）')) {
                                                document.getElementById('soft-delete-post').submit()
                                            }
                                        "
                                        type="button"
                                        role="menuitem"
                                        tabindex="-1"
                                        class="flex items-start w-full px-4 py-2 rounded-md hover:bg-gray-200 active:bg-gray-100
                                        dark:hover:bg-gray-400"
                                    >
                                        <i class="bi bi-file-earmark-x-fill"></i><span class="ml-2">刪除標記</span>
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- 文章資訊 --}}
                    <div class="flex items-center text-gray-400 mt-4 space-x-2">
                        {{-- 分類 --}}
                        <div>
                            <a class="hover:text-gray-700 dark:hover:text-gray-50"
                            href="{{ $post->category->link_with_name }}" title="{{ $post->category->name }}">
                                <i class="{{ $post->category->icon }}"></i><span class="ml-2">{{ $post->category->name }}</span>
                            </a>
                        </div>

                        <div>&bull;</div>

                        {{-- 作者 --}}
                        <div>
                            <a class="hover:text-gray-700 dark:hover:text-gray-50"
                            href="{{ route('users.show', ['user' => $post->user_id]) }}"
                            title="{{ $post->user->name }}">
                                <i class="bi bi-person-fill"></i><span class="ml-2">{{ $post->user->name }}</span>
                            </a>
                        </div>

                        <div class="hidden md:block">&bull;</div>

                        {{-- 發布時間 --}}
                        <div class="hidden md:block">
                            <a class="hover:text-gray-700 dark:hover:text-gray-50"
                            href="{{ $post->link_with_slug }}"
                            title="文章發布於：{{ $post->created_at }}">
                                <i class="bi bi-clock-fill"></i><span class="ml-2">{{ $post->created_at->diffForHumans() }}</span>
                            </a>
                        </div>

                        <div class="hidden md:block">&bull;</div>

                        {{-- 留言數 --}}
                        <div class="hidden md:block">
                            <a class="hover:text-gray-700 dark:hover:text-gray-50"
                            href="{{ $post->link_with_slug }}#comments">
                                <i class="bi bi-chat-square-text-fill"></i><span class="ml-2">{{ $post->comment_count }}</span>
                            </a>
                        </div>
                    </div>

                    {{-- 文章標籤 --}}
                    <div class="flex items-center mt-4 space-x-2">
                        @if ($post->tags()->exists())
                            <span class="text-green-400"><i class="bi bi-tags-fill"></i></span>

                            @foreach ($post->tags as $tag)
                                <a href="{{ route('tags.show', ['tag' => $tag->id]) }}"
                                class="text-xs inline-flex items-center font-bold leading-sm uppercase px-3 py-1 m-1
                                bg-green-200 hover:bg-green-400 active:bg-green-200 text-green-700 rounded-full shadow-lg ring-1 ring-green-700">
                                    {{ $tag->name }}
                                </a>
                            @endforeach
                        @endif
                    </div>

                    {{-- 文章內容 --}}
                    <div class="mt-4 ck-content dark:text-gray-50">
                        {!! $post->body !!}
                    </div>

                    {{-- 分享文章 --}}
                    <div class="mt-4 flex justify-end space-x-4">
                        <button type="button" title="分享此篇文章至 Facebook" data-sharer="facebook" data-url="{{ request()->fullUrl() }}"
                        class="text-4xl text-gray-400 hover:text-gray-700 duration-300
                        dark:hover:text-gray-50">
                            <i class="bi bi-facebook"></i>
                        </button>

                        <button type="button" title="分享此篇文章至 Twitter" data-sharer="twitter" data-url="{{ request()->fullUrl() }}"
                        class="text-4xl text-gray-400 hover:text-gray-700 duration-300
                        dark:hover:text-gray-50">
                            <i class="bi bi-twitter"></i>
                        </button>
                    </div>

                    {{-- 作者簡介 --}}
                    <div class="flex justify-start items-center border-t-2 border-gray-700 mt-4 pt-4
                    dark:border-white">
                        <div class="flex-none none md:flex md:justify-center md:items-center p-2 mr-4">
                            <img class="rounded-full h-16 w-16" src="{{ $post->user->gravatar(200) }}">
                        </div>
                        <div>
                            <a
                                href="{{ route('users.show', ['user' => $post->user->id]) }}"
                                class="text-2xl font-bold fancy-link
                                dark:text-gray-50"
                            >
                                {{ $post->user->name }}
                            </a>
                            <div class="dark:text-gray-50">{!! nl2br(e($post->user->introduction)) !!}</div>
                        </div>
                    </div>

                </x-card>

                {{-- 留言區塊 --}}
                @livewire('comment-box', ['post' => $post, 'commentCount' => $post->comment_count])
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {{-- 至頂按鈕 --}}
    <script src="{{ asset('js/scroll-to-top-btn.js') }}"></script>
    {{-- 文章中的嵌入影片顯示 --}}
    <script async charset="utf-8" src="{{ asset('embedly/platform.js') }}"></script>
    <script src="{{ asset('embedly/embedly.js') }}"></script>
    {{-- 程式碼區塊高亮 --}}
    <script src="{{ asset('prism/prism.js') }}"></script>
    {{-- 社交分享按鈕 --}}
    <script src="{{ asset('js/sharer.min.js') }}"></script>
@endsection
