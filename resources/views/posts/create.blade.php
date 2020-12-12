{{-- 新增文章 --}}
@extends('layouts.app')

@section('title', '新增文章')

@section('css')
    <link href="{{ asset('css/editor.css') }}" rel="stylesheet">
    <link href="{{ asset('css/tagify.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="container mb-5">
        <div class="d-flex justify-content-center">
            <div class="col-md-9">

                <div class="card shadow">

                    <h3 class="card-header"><i class="far fa-edit"></i> 新建文章</h3>

                    <div class="card-body">
                        <form action="{{ route('posts.store') }}" method="POST" accept-charset="UTF-8">
                            @csrf

                            {{-- 文章標題 --}}
                            <div class="mb-3">
                                <input class="form-control" type="text" name="title" value="{{ old('title') }}" placeholder="請填寫標題" required>
                            </div>

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

                            {{-- 文章標籤 --}}
                            <div class="mb-3">
                                <input class="form-control" type="text" id="tag-input" name="tags" value="{{ old('tags') }}" placeholder="輸入標籤（最多 5 個）">
                            </div>

                            {{-- 文章內容 --}}
                            <div class="mb-3">
                                <textarea name="body" id="editor" placeholder="請填寫文章內容～">{{ old('body') }}</textarea>
                            </div>

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
