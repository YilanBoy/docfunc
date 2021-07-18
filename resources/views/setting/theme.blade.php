{{-- 會員中心 --}}
@extends('layouts.app')

@section('title', '網站設定-主題')

@section('content')
    <div class="flex-grow container mx-auto max-w-7xl py-6">

        <div class="flex flex-col md:flex-row justify-center items-start px-4 xl:px-0">
            <x-card class="w-full md:w-1/2 flex flex-col justify-center mt-6 md:mt-0">
                <div class="text-gray-600 dark:text-white">網站主題</div>

                {{-- 明亮 / 暗黑模式切換 --}}
                <select id="theme-select" class="form-select h-10 w-full rounded-md shadow-sm border border-gray-300
                focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 mt-2
                dark:bg-gray-500 dark:text-white">
                    <option value="light">明亮</option>
                    <option value="dark">暗黑</option>
                </select>
            </x-card>
        </div>

    </div>
@endsection
