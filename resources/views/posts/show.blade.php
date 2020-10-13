{{-- 文章內容 --}}
@extends('layouts.app')

@section('title', $post->title)

@section('description', $post->excerpt)

@section('css')
    <link rel="stylesheet" href="{{ asset('css/content-styles.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('css/prism.css') }}" type="text/css">
@endsection

@section('content')
    <div class="container post-show-page">
        <div class="row">
            {{-- 文章 --}}
            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 post-content">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <h3 class="text-center mt-3 mb-3">
                            {{ $post->title }}
                        </h3>
                        <div class="article-meta text-center text-secondary">
                            <i class="far fa-folder"></i>
                            {{ $post->category->name }}
                            •
                            <i class="far fa-clock"></i>
                            {{ $post->created_at->diffForHumans() }}
                            •
                            <i class="far fa-comment"></i>
                            {{ $post->reply_count }}
                        </div>

                        <div class="article-meta text-center">
                            {{-- 文章標籤--}}
                            @if ($post->tags()->exists())
                                <i class="fas fa-tags text-info"></i>
                                @foreach ($post->tags as $tag)
                                    <a class="badge badge-pill badge-lg bgi-gradient text-white" href="{{ route('tags.show', $tag->id) }}" title="{{ $tag->name }}">
                                        {{ $tag->name }}
                                    </a>
                                @endforeach
                            @endif
                        </div>

                        <div class="ck-content mt-4 mb-4">
                            {!! $post->body !!}
                        </div>

                        @can('update', $post)
                            <div class="operate">
                                <hr>

                                <div class="float-right">
                                    <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-outline-secondary" role="button">
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
                <div class="card shadow post-reply mb-4">
                    <div class="card-body">
                        {{-- @includeWhen 可以依照條件來判斷要不要載入視圖 --}}
                        @includeWhen(Auth::check(), 'posts.reply-box', ['post' => $post])
                        @if ($post->replies->count() > 0)
                            {{-- latest() 等於 orderBy('created_at', 'desc') --}}
                            @include('posts.reply-list', ['replies' => $post->replies()->latest()->with('user')->get()])
                        @else
                            <div class="mt-1 mb-1">目前沒有任何評論~</div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- 側邊欄 --}}
            <div class="col-lg-3 col-md-3 hidden-sm hidden-xs author-info">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="text-center">
                            作者：{{ $post->user->name }}
                        </div>
                        <hr>
                        <div class="media">
                            <div align="center">
                                <a href="{{ route('users.show', $post->user->id) }}">
                                <img class="thumbnail img-fluid rounded-lg" src="{{ $post->user->gravatar('300') }}" width="300px" height="300px">
                                </a>
                            </div>
                        </div>
                        <hr>
                        <div>
                            {{ $post->user->introduction }}
                        </div>
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
