<!-- 文章內容 -->
@extends('layouts.app')

@section('title', $post->title)

@section('description', $post->excerpt)

@section('css')
    <link rel="stylesheet" href="{{ asset('css/content-styles.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/prism.css') }}" type="text/css">
@endsection

@section('content')
    <div class="container mx-auto max-w-7xl mt-6">
        <div class="flex justify-center items-center px-4 xl:px-0">

            <div class="relative w-full xl:w-2/3 shadow-md bg-white rounded-xl ring-1 ring-black ring-opacity-20 p-6">
                <h1 class="text-3xl font-bold">{{ $post->title }}</h1>

                <div class="flex items-center text-gray-400 mt-4 space-x-2">
                    {{-- 文章分類資訊 --}}
                    <div>
                        <a class="hover:text-gray-700"
                        href="{{ $post->category->link_with_name }}" title="{{ $post->category->name }}">
                            <i class="{{ $post->category->icon }}"></i><span class="ml-2">{{ $post->category->name }}</span>
                        </a>
                    </div>

                    <div>&bull;</div>

                    {{-- 文章作者資訊 --}}
                    <div>
                        <a class="hover:text-gray-700"
                        href="{{ route('users.show', ['user' => $post->user_id]) }}"
                        title="{{ $post->user->name }}">
                            <i class="bi bi-person-fill"></i><span class="ml-2">{{ $post->user->name }}</span>
                        </a>
                    </div>

                    <div class="hidden md:block">&bull;</div>

                    {{-- 文章發布時間 --}}
                    <div class="hidden md:block">
                        <a class="hover:text-gray-700"
                        href="{{ $post->link_with_slug }}"
                        title="文章發布於：{{ $post->created_at }}">
                            <i class="bi bi-clock-fill"></i><span class="ml-2">{{ $post->created_at->diffForHumans() }}</span>
                        </a>
                    </div>

                    <div class="hidden md:block">&bull;</div>

                    <div class="hidden md:block">
                        {{-- 文章留言數 --}}
                        <a class="hover:text-gray-700"
                        href="{{ $post->link_with_slug }}#replies-card">
                            <i class="bi bi-chat-square-text-fill"></i><span class="ml-2">{{ $post->reply_count }}</span>
                        </a>
                    </div>
                </div>

                <div class="flex items-center mt-4 space-x-2">
                    <!-- 文章標籤-->
                    @if ($post->tags()->exists())
                        <span class="text-green-700"><i class="bi bi-tags-fill"></i></span>

                        @foreach ($post->tags as $tag)
                            <a href="{{ route('tags.show', ['tag' => $tag->id]) }}"
                            class="text-xs inline-flex items-center font-bold leading-sm uppercase px-3 py-1 m-1
                            bg-green-200 hover:bg-green-400 active:bg-green-200 text-green-700 rounded-full shadow-lg ring-1 ring-green-700">
                                {{ $tag->name }}
                            </a>
                        @endforeach
                    @endif
                </div>

                <div class="mt-4">
                    {!! $post->body !!}
                </div>
            </div>
        </div>
    </div>

    {{-- <!-- 返回頂部按鈕的 position 改為 absolute 時，上一層樣式需要設定 relative  -->
    <div class="position-relative">
        <!-- 返回頂部按鈕 -->
        <button id="scroll-to-top-btn" title="Go to top"
        style="z-index: 99;bottom: 30px;right: 30px;"
        class="btn btn-danger rounded-circle shadow d-none position-fixed">
            <i class="fas fa-arrow-up fa-2x"></i>
        </button>

        <div class="container mb-5">
            <div class="row justify-content-md-center">
                <div class="position-relative col-12 col-xl-8">

                    <!-- 編輯區塊 -->
                    @can('update', $post)
                        <div
                            class="d-none d-xl-block position-absolute"
                            style="
                                z-index: 99;
                                top:0;
                                left: 101%;
                                width: 90px;
                                height: 100%;"
                        >
                            <div class="position-sticky" style="top: 30px">
                                <a role="button"  class="btn btn-success w-100 shadow mb-2"
                                href="{{ route('posts.edit', ['post' => $post->id]) }}">
                                    <i class="far fa-edit"></i> 編輯
                                </a>

                                <form id="delete-post" action="{{ route('posts.destroy', ['post' => $post->id]) }}" method="POST"
                                class="d-none"
                                onsubmit="return confirm('您確定要刪除此文章嗎？')">
                                    @csrf
                                    @method('DELETE')
                                </form>

                                <button type="submit" form="delete-post" class="btn btn-danger w-100 shadow">
                                    <i class="far fa-trash-alt"></i> 刪除
                                </button>
                            </div>
                        </div>

                        <div class="d-xl-none d-flex justify-content-end mb-2">
                            <a role="button"  class="btn btn-success shadow me-2"
                            href="{{ route('posts.edit', ['post' => $post->id]) }}">
                                <i class="far fa-edit"></i> 編輯
                            </a>

                            <button type="submit" form="delete-post" class="btn btn-danger shadow">
                                <i class="far fa-trash-alt"></i> 刪除
                            </button>
                        </div>
                    @endcan

                    <!-- 文章 -->
                    <div class="card shadow mb-4">
                        <div class="card-body p-3 p-md-4">

                            <h1 class="text-start fw-bold mb-2">{{ $post->title }}</h1>

                            <div class="d-flex justify-content-start text-secondary mb-2">
                                <a href="{{ $post->category->link_with_name }}"
                                class="d-block link-secondary text-decoration-none">
                                    <i class="{{ $post->category->icon }}"></i> {{ $post->category->name }}
                                </a>

                                <span class="text-secondary mx-2">&bull;</span>

                                <a href="{{ route('users.show', ['user' => $post->user->id]) }}"
                                class="d-block link-secondary text-decoration-none">
                                    <i class="fas fa-user"></i> {{ $post->user->name }}
                                </a>

                                <span class="d-none d-md-block text-secondary mx-2">&bull;</span>

                                <span class="d-none d-md-block">
                                    <i class="fas fa-clock"></i> {{ $post->created_at->diffForHumans() }}
                                </span>

                                <span class="d-none d-md-block text-secondary mx-2">&bull;</span>

                                <span class="d-none d-md-block">
                                    <i class="fas fa-comment"></i> {{ $post->reply_count }}
                                </span>
                            </div>

                            <div class="text-start mb-4">
                                <!-- 文章標籤-->
                                @if ($post->tags()->exists())
                                    <span class="text-primary"><i class="fas fa-tags"></i></span>
                                    @foreach ($post->tags as $tag)
                                        <a role="button" class="btn btn-primary btn-sm rounded-pill py-0 shadow mb-1"
                                        href="{{ route('tags.show', ['tag' => $tag->id])}}">
                                            {{ $tag->name }}
                                        </a>
                                    @endforeach
                                @endif
                            </div>

                            <!-- 文章內容 -->
                            <div class="ck-content mb-4">
                                {!! $post->body !!}
                            </div>

                            <!-- 分享文章 -->
                            <div class="d-flex justify-content-end">
                                <button type="button" title="分享此篇文章至 Facebook" data-sharer="facebook" data-url="{{ request()->fullUrl() }}"
                                class="btn btn-link link-secondary text-decoration-none">
                                    <i class="fab fa-facebook-square fa-2x"></i>
                                </button>
                                <button type="button" title="分享此篇文章至 Twitter" data-sharer="twitter" data-url="{{ request()->fullUrl() }}"
                                class="btn btn-link link-secondary text-decoration-none">
                                    <i class="fab fa-twitter fa-2x"></i>
                                </button>
                            </div>

                            <hr>
                            <!-- 作者 -->
                            <div class="d-flex justify-content-start align-items-center">
                                <div class="d-none d-md-block align-self-center me-4">
                                    <img class="rounded-circle" src="{{ $post->user->gravatar() }}" width="60px" height="60px">
                                </div>
                                <div class="d-flex flex-column">
                                    <label class="fs-4 fw-bold">
                                        <a class="link-dark text-decoration-none" href="{{ route('users.show', ['user' => $post->user->id]) }}">
                                            {{ $post->user->name }}
                                        </a>
                                    </label>
                                    <span class="text-dark">{!! nl2br(e($post->user->introduction)) !!}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 回覆區塊 -->
                    @livewire('replies', ['post' => $post])
                </div>
            </div>
        </div>
    </div> --}}
@endsection

@section('scripts')
    <!-- 至頂按鈕 -->
    <script src="{{ asset('js/scroll-to-top-btn.js') }}"></script>
    <!-- 文章中的嵌入影片顯示 -->
    <script async charset="utf-8" src="{{ asset('js/platform.js') }}"></script>
    <script src="{{ asset('js/embedly.js') }}"></script>
    <!-- 程式碼區塊高亮 -->
    <script src="{{ asset('js/prism.js') }}"></script>
    <!-- 社交分享按鈕 -->
    <script src="{{ asset('js/sharer.min.js') }}"></script>
@endsection
