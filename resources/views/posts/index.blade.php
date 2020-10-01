{{-- 文章列表 --}}
@extends('layouts.app')

@section('title', isset($category) ? $category->name : '所有文章')

@section('content')
    <div class="row mb-5">
        <div class="col-lg-9 col-md-9 post-list">

            {{-- 分類訊息區塊 --}}
            @if (isset($category))
                <div class="alert alert-primary border border-primary" role="alert">
                    <strong>{{ $category->name }}</strong> : {{ $category->description }}
                </div>
            @endif

            {{-- 標籤訊息區塊 --}}
            @if (isset($tag))
                <div class="alert alert-primary border border-primary" role="alert">
                    標籤：<strong>{{ $tag->name }}</strong>
                </div>
            @endif

            <div class="card shadow-lg mb-4">

                {{-- 文章排序選擇 --}}
                <div class="card-header bg-transparent">
                    <ul class="nav nav-pills">
                        <li class="nav-item">
                            <a class="nav-link
                            @if (!str_contains(request()->fullUrl(), 'recent'))
                                active
                            @endif"
                            href="{{ request()->url() }}?order=latest">最新發佈</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link
                            @if (str_contains(request()->fullUrl(), 'recent'))
                                active
                            @endif"
                            href="{{ request()->url() }}?order=recent">最後回覆</a>
                        </li>
                    </ul>
                </div>

                {{-- 文章列表區塊 --}}
                <div class="card-body">
                    @if ($posts->count())
                        @include('posts.list', ['posts' => $posts])

                        {{-- 分頁 --}}
                        <div class="mt-5">
                            {{-- onEachSide 會限制當前分頁左右顯示的分頁數目 --}}
                            {{-- withQueryString 會把 Url 中所有的查詢參數值添加到分頁鏈接 --}}
                            {{ $posts->onEachSide(1)->withQueryString()->links() }}
                        </div>
                    @else
                        <div class="d-flex justify-content-center p-5">目前此分類下沒有文章喔 ~_~ </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- 文章列表側邊欄區塊 --}}
        <div class="col-lg-3 col-md-3 sidebar">
            @include('posts.sidebar')
        </div>
    </div>
@endsection
