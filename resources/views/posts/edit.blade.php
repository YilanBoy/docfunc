{{-- 編輯文章 --}}
@extends('layouts.app')

@section('title', '編輯文章')

@section('css')
    <link href="{{ asset('css/missing-content-styles.css') }}" rel="stylesheet">
    <link href="{{ asset('css/editor.css') }}" rel="stylesheet">
    <link href="{{ asset('css/tagify.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="container mx-auto max-w-7xl mt-6">
        <div class="flex justify-center items-center px-4 xl:px-0">

            <div class="w-full xl:w-2/3 space-y-6 flex flex-col justify-center items-center">
                {{-- Title --}}
                <div class="fill-current text-gray-700 text-2xl">
                    <i class="bi bi-pencil"></i><span class="ml-4">編輯文章</span>
                </div>

                <div class="relative w-full shadow-md bg-white rounded-xl ring-1 ring-black ring-opacity-20 p-6">

                    <div
                        class="hidden xl:block absolute z-10 top-0 left-103/100 w-52 h-full"
                    >
                        <div class="sticky top-9 flex flex-col">
                            <div class="w-full flex justify-center items-center bg-gradient-to-r from-white to-white/0
                            rounded-xl p-4">
                                <span class="update-characters"></span>
                            </div>

                            {{-- Save Button --}}
                            <button type="submit" id="lg-save-post" form="edit-post"
                            class="flex justify-center items-center h-16 w-16 text-2xl text-white font-bold
                            bg-gray-600 hover:bg-gray-800 active:bg-gray-600 rounded-full
                            transform hover:scale-125 transition duration-150 ease-in shadow-md hover:shadow-xl mt-4">
                                <i class="bi bi-save2-fill"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Validation Errors --}}
                    <x-auth-validation-errors class="mb-4" :errors="$errors" />

                    <form id="edit-post" action="{{ route('posts.update', ['post' => $post->id]) }}" method="POST" >
                        @method('PUT')
                        @csrf

                        {{-- Title --}}
                        <div class="relative mt-5">
                            <input
                                id="title"
                                name="title"
                                type="text"
                                placeholder="標題"
                                value="{{ old('title', $post->title ) }}"
                                required
                                autofocus
                                class="peer h-10 w-full border-b-2 border-gray-300 text-gray-900
                                placeholder-transparent focus:outline-none focus:border-blue-600"
                            >

                            <label
                                for="title"
                                class="absolute left-0 -top-3.5 text-gray-600 text-sm
                                transition-all peer-placeholder-shown:text-base peer-placeholder-shown:text-gray-400 peer-placeholder-shown:top-2
                                peer-focus:-top-3.5 peer-focus:text-gray-600 peer-focus:text-sm"
                            >
                                標題
                            </label>
                        </div>

                        {{-- Category --}}
                        <div class="mt-5">
                            <label for="category_id" class="hidden">分類</label>

                            <select
                                name="category_id"
                                required
                                class="outline-none h-10 w-full rounded-md shadow-sm border border-gray-300
                                focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
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
                                class="h-10 rounded-md text-sm"
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
                            <x-button id="save-post">
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
