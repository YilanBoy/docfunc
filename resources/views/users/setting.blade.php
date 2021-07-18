@extends('users.index')

@section('title', '會員中心-網站設定')

@section('users.content')

    <span class="text-gray-600 dark:text-white">網站主題</span>

    {{-- 明亮 / 暗黑模式切換 --}}
    <div class="flex space-x-2 mt-2">
        <span class="text-gray-800 dark:text-gray-500"><i class="bi bi-brightness-high-fill"></i></span>
        <label for="theme-switch"
        class="w-9 h-6 flex items-center bg-gray-300 rounded-full p-1 cursor-pointer duration-300 ease-in-out dark:bg-gray-800">
            <div class="bg-white w-4 h-4 rounded-full shadow-md transform duration-300 ease-in-out dark:translate-x-3"></div>
        </label>
        <span class="text-gray-200 dark:text-white"><i class="bi bi-moon-fill"></i></span>
    </div>

@endsection
