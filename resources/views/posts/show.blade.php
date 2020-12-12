{{-- 文章內容 --}}
@extends('layouts.app')

@section('title', $post->title)

@section('description', $post->excerpt)

@section('css')
    <link rel="stylesheet" href="{{ asset('css/content-styles.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/prism.css') }}" type="text/css">
@endsection

@section('content')
    <div class="container mb-5">
        <div class="d-flex justify-content-center">
            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                {{-- 文章 --}}
                <div class="card shadow mb-4">
                    <div class="card-body p-5">
                        <h3 class="text-center mb-2">{{ $post->title }}</h3>

                        <div class="text-center text-secondary mb-2">
                            <i class="far fa-user"></i>
                            <a class="link-secondary text-decoration-none"
                            href="{{ route('users.show', $post->user->id) }}">{{ $post->user->name }}</a>
                            •
                            <i class="far fa-folder"></i>
                            <a class="link-secondary text-decoration-none"
                            href="{{ $post->category->linkWithName() }}">{{ $post->category->name }}</a>
                            •
                            <i class="far fa-clock"></i>
                            {{ $post->created_at->diffForHumans() }}
                            •
                            <i class="far fa-comment"></i>
                            {{ $post->reply_count }}
                        </div>

                        <div class="text-center mb-4">
                            {{-- 文章標籤--}}
                            @if ($post->tags()->exists())
                                <i class="fas fa-tags text-primary"></i>
                                @foreach ($post->tags as $tag)
                                    <a role="button" class="btn btn-primary btn-sm rounded-pill py-0 mb-1"
                                    href="{{ route('tags.show', $tag->id) }}">
                                        {{ $tag->name }}
                                    </a>
                                @endforeach
                            @endif
                        </div>

                        <div class="ck-content mb-4">
                            {!! $post->body !!}
                        </div>

                        <div class="bg-secondary d-flex justify-content-start align-items-center p-4" style="height: 120px;">
                            <div class="align-self-center me-4">
                                <img class="rounded-circle" src="{{ $post->user->gravatar() }}" width="60px" height="60px">
                            </div>
                            <div class="d-flex flex-column">
                                <h4>
                                    <a class="link-light text-decoration-none" href="{{ route('users.show', $post->user->id) }}">
                                        {{ $post->user->name }}
                                    </a>
                                </h4>
                                <span class="text-white">{{ $post->user->introduction }}</span>
                            </div>
                        </div>

                        @can('update', $post)
                            <div class="operate">
                                <hr>

                                <div class="d-flex justify-content-end">
                                    <a role="button"  class="btn btn-outline-secondary me-2"
                                    href="{{ route('posts.edit', $post->id) }}">
                                        <i class="far fa-edit mr-2"></i>編輯
                                    </a>

                                    <form action="{{ route('posts.destroy', $post->id) }}" method="POST"
                                    style="display: inline-block;"
                                    onsubmit="return confirm('您確定要刪除嗎？')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-outline-secondary">
                                            <i class="far fa-trash-alt mr-2"></i>刪除
                                        </button>
                                    </form>
                                </div>

                            </div>
                        @endcan
                    </div>
                </div>

                {{-- 會員回覆列表 --}}
                <div class="card shadow mb-4">
                    <div class="card-body p-5">
                        {{-- @includeWhen 可以依照條件來判斷要不要載入視圖 --}}
                        @includeWhen(Auth::check(), 'posts.reply-box', ['post' => $post])

                        @if ($post->replies->count() > 0)
                            {{-- latest() 等於 orderBy('created_at', 'desc') --}}
                            @include('posts.reply-list', ['replies' => $post->replies()->latest()->with('user', 'post')->get()])
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {{-- 文章中的嵌入影片顯示 --}}
    <script async charset="utf-8" src="{{ asset('js/platform.js') }}"></script>
    <script src="{{ asset('js/embedly.js') }}"></script>

    {{-- 程式碼區塊高亮 --}}
    <script src="{{ asset('js/prism.js') }}"></script>
@endsection
