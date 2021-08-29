{{-- 新增文章 --}}
@extends('layouts.app')

@section('title', '新增文章')

@section('css')
    <link href="{{ asset('css/missing-content-styles.css') }}" rel="stylesheet">
    <link href="{{ asset('css/editor.css') }}" rel="stylesheet">
    <link href="{{ asset('css/tagify.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="container mx-auto max-w-7xl">
        <div class="min-h-screen flex justify-center items-start px-4 xl:px-0 mt-6">

            <div class="w-full xl:w-2/3 space-y-6 flex flex-col justify-center items-center">
                {{-- 頁面標題 --}}
                <div class="fill-current text-gray-700 text-2xl dark:text-white">
                    <i class="bi bi-pencil-fill"></i><span class="ml-4">新增文章</span>
                </div>

                {{-- 文章編輯資訊-桌面裝置 --}}
                <div class="relative w-full shadow-md bg-white rounded-xl ring-1 ring-black ring-opacity-20 p-5
                dark:bg-gray-600">

                    <div
                        class="hidden xl:block absolute top-0 left-103/100 w-52 h-full"
                    >
                        <div class="sticky top-9 flex flex-col">
                            {{-- 字數提示 --}}
                            <div class="w-full flex justify-start items-center bg-gradient-to-r from-white to-white/0 rounded-xl p-4
                            dark:text-white dark:from-gray-600 dark:to-gray-600/0">
                                <span class="update-post-characters"></span>
                            </div>

                            {{-- 儲存按鈕 --}}
                            <button type="submit" form="create-post"
                            class="save-post group relative w-16 h-16 inline-flex rounded-xl border border-blue-600 focus:outline-none mt-4">
                                <span class="absolute inset-0 inline-flex items-center justify-center self-stretch text-2xl text-white text-center font-medium bg-blue-600
                                rounded-xl ring-1 ring-blue-600 ring-offset-1 ring-offset-blue-600 transform transition-transform
                                group-hover:-translate-y-2 group-hover:-translate-x-2 group-active:-translate-y-0 group-active:-translate-x-0">
                                    <i class="bi bi-save2-fill"></i>
                                </span>
                            </button>
                        </div>
                    </div>

                    {{-- 驗證錯誤訊息 --}}
                    <x-auth-validation-errors class="mb-4" :errors="$errors" />

                    <form id="create-post" action="{{ route('posts.store') }}" method="POST">
                        @csrf

                        {{-- 文章標題 --}}
                        <div>
                            <label for="title" class="hidden">文章標題</label>

                            <input type="text" name="title" placeholder="文章標題" value="{{ old('title') }}" required autofocus
                            class="form-input w-full rounded-md shadow-sm border border-gray-300
                            focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 mt-2
                            dark:bg-gray-500 dark:text-white dark:placeholder-white">
                        </div>

                        {{-- 文章分類 --}}
                        <div class="mt-5">
                            <label for="category_id" class="hidden">分類</label>

                            <select
                                name="category_id"
                                required
                                class="form-select h-10 w-full rounded-md shadow-sm border border-gray-300
                                focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50
                                dark:bg-gray-500 dark:text-white"
                            >
                                <option value="" hidden disabled {{ old('category_id') ? '' : 'selected' }}>請選擇分類</option>
                                {{-- 這裡的 $categories 使用的是 view composer 來取得值，詳細可查看 ViewServiceProvider --}}
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id  ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- 文章標籤 --}}
                        <div class="mt-5">
                            <label for="tags" class="hidden">標籤 (最多 5 個)</label>

                            <input
                                id="tag-input"
                                type="text"
                                name="tags"
                                value="{{ old('tags') }}"
                                placeholder="標籤 (最多 5 個)"
                                class="h-10 rounded-md text-sm dark:bg-gray-500"
                            >
                        </div>

                        {{-- 文章內容 --}}
                        <div class="mt-5">
                            <label for="body" class="hidden">內文</label>

                            <textarea name="body" id="editor" placeholder="分享一些很棒的事情吧!">{{ old('body') }}</textarea>
                        </div>

                        {{-- 文章編輯資訊-行動裝置 --}}
                        <div class="flex xl:hidden justify-between items-center mt-4">
                            {{-- 顯示文章總字數 --}}
                            <div>
                                <span class="update-characters"></span>
                            </div>

                            {{-- 儲存按鈕 --}}
                            <x-button class="save-post">
                                <i class="bi bi-save2-fill"></i><span class="ml-2">儲存</span>
                            </x-button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {{-- 載入 Ckeditor --}}
    <script src="{{ asset('js/editor/ckeditor.js') }}"></script>
    <script src="{{ asset('js/editor.js') }}"></script>
    {{-- 載入 Tagify --}}
    <script src="{{ asset('js/tagify.js') }}"></script>
@endsection
