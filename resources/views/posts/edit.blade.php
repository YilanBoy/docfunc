{{-- 編輯文章 --}}
@extends('layouts.app')

@section('title', '編輯文章')

@section('css')
    <link href="{{ asset('css/editor.css') }}" rel="stylesheet">
    <link href="{{ asset('css/tagify.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="container mx-auto max-w-7xl mt-6">
        <div class="flex justify-center items-center px-4 xl:px-0">

            <div class="w-full xl:w-2/3 space-y-6 flex flex-col justify-center items-center"">
                {{-- Title --}}
                <div class="fill-current text-gray-700 text-2xl">
                    <i class="bi bi-pencil"></i><span class="ml-4">新增文章</span>
                </div>

                <div class="relative w-full shadow-md bg-white rounded-xl ring-1 ring-black ring-opacity-20 p-6">

                    <div
                        class="hidden xl:block absolute z-10 top-0 left-103/100 w-52 h-full"
                    >
                        <div class="sticky top-9 flex flex-col justify-center items-center shadow-md bg-white rounded-xl ring-1 ring-black ring-opacity-20 p-4">
                            <span class="w-full flex justify-center items-center update-characters border-b-2 border-gray-700 pb-4 mb-4"></span>
                            {{-- Save Button --}}
                            <x-button id="lg-save-post" form="edit-post" class="w-full flex justify-center items-center">
                                <i class="bi bi-save2-fill"></i><span class="ml-2">儲存</span>
                            </x-button>
                        </div>
                    </div>

                    {{-- Validation Errors --}}
                    <x-auth-validation-errors class="mb-4" :errors="$errors" />

                    <form id="edit-post" action="{{ route('posts.update', ['post' => $post->id]) }}" method="POST" >
                        @method('PUT')
                        @csrf

                        <div>
                            <x-label for="title" :value="'標題'" />

                            <x-input class="block mt-1 w-full" type="text" name="title" :value="old('title', $post->title )" required autofocus />
                        </div>

                        <div class="mt-4">
                            <x-label for="category_id" :value="'分類'" />

                            <select name="category_id" required
                            class="block mt-1 w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="" hidden disabled {{ $post->id ? '' : 'selected' }}>請選擇分類</option>
                                {{-- 這裡的 $categories 使用的是 View::composer() 方法取得值，寫在 ViewServiceProvider.php 中 --}}
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ $post->category_id == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mt-4">
                            <x-label for="tags" :value="'標籤（最多 5 個）'" />

                            <input class="block mt-1 w-full rounded-md" type="text" id="tag-input" name="tags" value="{{ old('tags', $post->tagsJson) }}">
                        </div>

                        <div class="mt-4">
                            <x-label for="body" :value="'內文'" class="mb-1" />

                            <textarea name="body" id="editor" placeholder="請填寫文章內容～">{{ old('body', $post->body ) }}</textarea>
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
