{{-- 新增文章 --}}
@extends('layouts.app')

@section('title', '新增文章')

@section('css')
    <link href="{{ asset('css/editor.css') }}" rel="stylesheet">
    <link href="{{ asset('css/tagify.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="position-relative">
        {{-- 移至底部按鈕 --}}
        <button  class="btn btn-primary d-block position-fixed"
        style="bottom: 30px;right: 30px;z-index: 99;"
        onclick="scrollToBottom()" id="scroll-to-bottom-btn" title="Go to bottom">
            <i class="fas fa-arrow-down"></i> 移至底部
        </button>

        <div class="container mb-5">
            <div class="row justify-content-md-center">
                <div class="col-12 col-xl-8">

                    <div class="card shadow">

                        <h3 class="card-header py-3"><i class="far fa-edit"></i> 新建文章</h3>

                        <div class="card-body">
                            <form action="{{ route('posts.store') }}" method="POST" accept-charset="UTF-8">
                                @csrf

                                {{-- 文章標題 --}}
                                <div class="mb-3">
                                    <input class="form-control" type="text" name="title" value="{{ old('title') }}" placeholder="請填寫標題" required>
                                </div>

                                @error('title')
                                    <div class="mb-3">
                                        <span class="text-danger">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    </div>
                                @enderror

                                {{-- 文章分類 --}}
                                <div class="mb-3">
                                    <select class="form-select" name="category_id" required>
                                        <option value="" hidden disabled {{ old('category_id') ? '' : 'selected' }}>請選擇分類</option>
                                        {{-- 這裡的 $categories 使用的是 View::composer() 方法取得值，寫在 ViewServiceProvider.php 中 --}}
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id  ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                @error('category_id')
                                    <div class="mb-3">
                                        <span class="text-danger">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    </div>
                                @enderror

                                {{-- 文章標籤 --}}
                                <div class="mb-3">
                                    <input class="form-control" type="text" id="tag-input" name="tags" value="{{ old('tags') }}" placeholder="輸入標籤（最多 5 個）">
                                </div>

                                {{-- 文章內容 --}}
                                <div class="mb-3">
                                    <textarea name="body" id="editor" placeholder="請填寫文章內容～">{{ old('body') }}</textarea>
                                </div>

                                @error('body')
                                    <div class="mb-3">
                                        <span class="text-danger">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    </div>
                                @enderror

                                <div class="d-flex justify-content-between align-items-center">
                                    {{-- 顯示文章總字數 --}}
                                    <span class="update-characters"></span>

                                    {{-- 儲存文章 --}}
                                    <button type="submit" id="post-save" class="btn btn-primary">
                                        <i class="far fa-save mr-2" aria-hidden="true"></i> 儲存
                                    </button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const tagArray = {!! $inputTags !!};
        const appUrl = "{{ config('app.url') }}";
    </script>
    {{-- 載入 Ckeditor --}}
    <script src="{{ asset('editor/build/ckeditor.js') }}"></script>
    <script src="{{ asset('js/editor.js') }}"></script>
    {{-- 載入 Tagify --}}
    <script src="{{ asset('js/tagify.min.js') }}"></script>
    <script src="{{ asset('js/tagify-input.js') }}"></script>
    {{-- 至底按鈕 --}}
    <script src="{{ asset('js/scroll-to-bottom-btn.js') }}"></script>
@endsection
