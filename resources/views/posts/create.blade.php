{{-- 新增文章 --}}
@extends('layouts.app')

@section('title', '新增文章')

@section('css')
    <link href="{{ asset('css/editor.css') }}" rel="stylesheet">
    <link href="{{ asset('css/tagify.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <h2 class="">
                            <i class="far fa-edit"></i>
                            新建文章
                        </h2>
                        <hr>
                        <form action="{{ route('posts.store') }}" method="POST" accept-charset="UTF-8">

                            @csrf

                            @include('shared.error')

                            {{-- 文章標題 --}}
                            <div class="form-group">
                                <input class="form-control" type="text" name="title" value="{{ old('title') }}" placeholder="請填寫標題" required />
                            </div>

                            {{-- 文章分類 --}}
                            <div class="form-group">
                                <select class="form-control" name="category_id" required>
                                    <option value="" hidden disabled {{ old('category_id') ? '' : 'selected' }}>請選擇分類</option>
                                    {{-- 這裡的 $categories 使用的是 View::composer() 方法取得值，寫在 ViewServiceProvider.php 中 --}}
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id  ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- 文章標籤 --}}
                            <div class="form-group">
                                <input class="form-control" type="text" id="tag-input" name="tags" value="{{ old('tags') }}" placeholder="輸入標籤（最多 5 個）" />
                            </div>

                            {{-- 文章內容 --}}
                            <div class="form-group">
                                <textarea name="body" id="editor" placeholder="請填寫文章內容～">{{ old('body') }}</textarea>
                            </div>

                            <div class="update-controls">
                                {{-- 顯示文章總字數 --}}
                                <span class="update-characters"></span>

                                {{-- 儲存文章 --}}
                                <button type="submit" id="post-save" class="btn btn-primary float-right"><i class="far fa-save mr-2" aria-hidden="true"></i>儲存</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        var tagArray = {!! $inputTags !!};
        var appUrl = "{{ config('app.url') }}";
    </script>
    {{-- 載入 Ckeditor --}}
    <script src="{{ asset('editor/build/ckeditor.js') }}"></script>
    <script src="{{ asset('js/editor.js') }}"></script>
    {{-- 載入 Tagify --}}
    <script src="{{ asset('js/tagify.min.js') }}"></script>
    <script src="{{ asset('js/tagify.input.js') }}"></script>
@endsection
