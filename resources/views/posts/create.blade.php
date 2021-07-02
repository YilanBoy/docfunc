{{-- 新增文章 --}}
@extends('layouts.app')

@section('title', '新增文章')

@section('css')
    <link href="{{ asset('css/editor.css') }}" rel="stylesheet">
    <link href="{{ asset('css/tagify.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="position-relative">
        <div class="container mb-5">
            <div class="row justify-content-md-center">
                <div class="position-relative col-12 col-xl-8">

                    <div
                        class="d-none d-xl-block position-absolute"
                        style="
                        z-index: 99;
                        top:0;
                        left: 101%;
                        width: 200px;
                        height: 100%;"
                    >
                        <div class="position-sticky card" style="top: 30px">
                            <div class="d-flex flex-column card-body">
                                <span class="fw-bold">文章字數</span>
                                {{-- 顯示文章總字數 --}}
                                <span class="update-characters"></span>
                                <hr>
                                {{-- 儲存文章 --}}
                                <button type="submit" id="lg-save-post" form="create-post" class="btn btn-primary shadow">
                                    <i class="far fa-save" aria-hidden="true"></i> 儲存
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow">

                        <h5 class="card-header py-3"><i class="fas fa-edit"></i> 新建文章</h5>

                        <div class="card-body">
                            <form id="create-post" action="{{ route('posts.store') }}" method="POST" accept-charset="UTF-8">
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
                                <div class="mb-3 mb-xl-0">
                                    <textarea name="body" id="editor" placeholder="請填寫文章內容～">{{ old('body') }}</textarea>
                                </div>

                                @error('body')
                                    <div class="mb-3 mb-xl-0 mt-xl-3">
                                        <span class="text-danger">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    </div>
                                @enderror

                                <div class="d-flex d-xl-none justify-content-between align-items-center">
                                    {{-- 顯示文章總字數 --}}
                                    <div>
                                        <span class="fw-bold">文章字數：</span>
                                        <span class="update-characters"></span>
                                    </div>

                                    {{-- 儲存文章 --}}
                                    <button type="submit" id="save-post" class="btn btn-primary shadow">
                                        <i class="far fa-save" aria-hidden="true"></i> 儲存
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
        const appUrl = "{{ config('app.url') }}";
    </script>
    {{-- 載入 Ckeditor --}}
    <script src="{{ asset('js/editor/build/ckeditor.js') }}"></script>
    <script src="{{ asset('js/editor.js') }}"></script>
    {{-- 載入 Tagify --}}
    <script src="{{ asset('js/tagify.js') }}"></script>
@endsection
