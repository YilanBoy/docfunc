{{-- 文章列表 --}}
@extends('layouts.app')

@section('title', isset($category) ? $category->name : '所有文章')

@section('content')
    <div class="container mb-5">
        <div class="row justify-content-md-center g-4">
            <div class="col-12 col-xl-8">
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

                @livewire('posts', [
                    'currentUrl' => url()->current(),
                    'category' => isset($category) ? $category : null,
                    'tag' => isset($tag) ? $tag : null,
                ])

            </div>

            {{-- 文章列表側邊欄區塊 --}}
            <div class="col-12 col-xl-3">
                @include('posts.sidebar')
            </div>
        </div>
    </div>
@endsection
