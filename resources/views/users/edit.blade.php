@extends('users.index')

@section('title', '會員中心-編輯個人資料')

@section('users.content')
    <div class="flex flex-col justify-center items-center mb-8">
        {{-- 大頭貼照片 --}}
        <div>
            <img class="rounded-full h-36 w-36" src="{{ $user->gravatar('500') }}" alt="{{ $user->name }}">
        </div>

        <div class="flex mt-4 dark:text-white">
            <span class="mr-2">會員大頭貼由</span>
            <a href="https://zh-tw.gravatar.com/" target="_blank" rel="nofollow noopener noreferrer"
            class="text-gray-400 hover:text-gray-700 dark:hover:text-white">Gravatar</a>
            <span class="ml-2">技術提供</span>
        </div>
    </div>

    {{-- 驗證錯誤訊息 --}}
    <x-auth-validation-errors class="mb-4" :errors="$errors" />

    <form method="POST" action="{{ route('users.update', ['user' => $user->id]) }}" class="w-full">
        @method('PUT')
        @csrf

        {{-- 信箱 --}}
        <div class="mt-5">
            <div class="text-gray-600 dark:text-white">信箱</div>
            <div class="text-black dark:text-white">{{ $user->email }}</div>
        </div>

        {{-- 會員名稱 --}}
        <div class="mt-10">
            <x-floating-label-input
                :type="'text'"
                :name="'name'"
                :placeholder="'會員名稱 (只能使用英文、數字、_ 或是 -)'"
                :value="old('name', $user->name)"
                required
                autofocus
            ></x-floating-label-input>
        </div>

        {{-- 會員自介 --}}
        <div class="mt-5">
            <label for="introduction" class="text-gray-600 dark:text-white">個人介紹 (最多 80 個字)</label>

            <textarea
                name="introduction"
                placeholder="介紹一下你自己吧！"
                rows="5"
                class="form-textarea w-full rounded-md shadow-sm border border-gray-300
                focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 mt-2
                dark:bg-gray-500 dark:text-white dark:placeholder-white"
            >{{ old('introduction', $user->introduction) }}</textarea>
        </div>

        <div class="flex items-center justify-end mt-4">
            {{-- 儲存按鈕 --}}
            <x-button>
                <i class="bi bi-save2-fill"></i><span class="ml-2">儲存</span>
            </x-button>
        </div>
    </form>
@endsection
