{{-- 編輯文章 --}}
@extends('layouts.app')

@section('title', '編輯文章')

@section('css')
    <link href="{{ asset('css/missing-content-styles.css') }}" rel="stylesheet">
    <link href="{{ asset('css/editor.css') }}" rel="stylesheet">
    <link href="{{ asset('css/tagify.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="container mx-auto max-w-7xl">
        <div class="min-h-screen flex justify-center items-center px-4 xl:px-0 mt-6">

            <div class="w-full xl:w-2/3 space-y-6 flex flex-col justify-center items-center">
                {{-- Title --}}
                <div class="fill-current text-gray-700 text-2xl dark:text-white">
                    <i class="bi bi-pencil-fill"></i><span class="ml-4">編輯文章</span>
                </div>

                <div class="relative w-full shadow-md bg-white rounded-xl ring-1 ring-black ring-opacity-20 p-5
                dark:bg-gray-600">

                    <div
                        class="hidden xl:block absolute top-0 left-103/100 w-52 h-full"
                    >
                        <div class="sticky top-9 flex flex-col">
                            <div class="w-full flex justify-start items-center bg-gradient-to-r from-white to-white/0 rounded-xl p-4
                            dark:text-white dark:from-gray-600 dark:to-gray-600/0">
                                <span class="update-post-characters"></span>
                            </div>

                            {{-- Save Button --}}
                            <button type="submit" form="edit-post"
                            class="save-post group relative w-16 h-16 inline-flex rounded-xl border border-blue-600 focus:outline-none mt-4">
                                <span class="absolute inset-0 inline-flex items-center justify-center self-stretch text-2xl text-white text-center font-medium bg-blue-600
                                rounded-xl ring-1 ring-blue-600 ring-offset-1 ring-offset-blue-600 transform transition-transform
                                group-hover:-translate-y-2 group-hover:-translate-x-2 group-active:-translate-y-0 group-active:-translate-x-0">
                                    <i class="bi bi-save2-fill"></i>
                                </span>
                            </button>
                        </div>
                    </div>

                    {{-- Validation Errors --}}
                    <x-auth-validation-errors class="mb-4" :errors="$errors" />

                    <form id="edit-post" action="{{ route('posts.update', ['post' => $post->id]) }}" method="POST" >
                        @method('PUT')
                        @csrf

                        {{-- Title --}}
                        <div class="mt-5">
                            <x-floating-label-input
                                :type="'text'"
                                :name="'title'"
                                :placeholder="'文章標題'"
                                :value="old('title', $post->title )"
                                required
                                autofocus
                            ></x-floating-label-input>
                        </div>

                        {{-- Category --}}
                        <div class="mt-5">
                            <label for="category_id" class="hidden">分類</label>

                            <select
                                name="category_id"
                                required
                                class="form-select h-10 w-full rounded-md shadow-sm border border-gray-300
                                focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50
                                dark:bg-gray-500 dark:text-white"
                            >
                                <option value="" hidden disabled {{ $post->id ? '' : 'selected' }}>請選擇分類</option>
                                {{-- 這裡的 $categories 使用的是 View::composer() 方法取得值，寫在 ViewServiceProvider.php 中 --}}
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ $post->category_id == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Tags --}}
                        <div class="mt-5">
                            <label for="tags" class="hidden">標籤（最多 5 個）</label>

                            <input
                                id="tag-input"
                                type="text"
                                name="tags"
                                value="{{ old('tags', $post->tagsJson) }}"
                                placeholder="標籤（最多 5 個）"
                                class="h-10 rounded-md text-sm dark:bg-gray-500"
                            >
                        </div>

                        {{-- Body --}}
                        <div class="mt-5">
                            <label for="body" class="hidden">內文</label>

                            <textarea name="body" id="editor" placeholder="分享一些很棒的事情吧!">{{ old('body', $post->body ) }}</textarea>
                        </div>

                        <div class="flex xl:hidden justify-between items-center mt-4">
                            {{-- 顯示文章總字數 --}}
                            <div>
                                <span class="update-characters"></span>
                            </div>

                            {{-- Save Button --}}
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
    <script src="{{ asset('js/editor/build/ckeditor.js') }}"></script>
    <script src="{{ asset('js/editor.js') }}"></script>
    {{-- 載入 Tagify --}}
    <script src="{{ asset('js/tagify.js') }}"></script>
@endsection
