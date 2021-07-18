{{-- 會員中心 --}}
@extends('layouts.app')

@section('content')
    <div class="flex-grow container mx-auto max-w-7xl py-6">
        <div class="flex flex-col md:flex-row justify-center items-start px-4 xl:px-0">

            <div class="w-full md:w-60 xl:w-80 md:mr-6">
                {{-- 選項 --}}
                <x-card class="w-full flex flex-col justify-center items-center
                dark:text-white">
                    <h3 class="w-full font-semibold text-lg text-center border-black border-b-2 pb-3 mb-3
                    dark:border-white">
                        <i class="bi bi-person-lines-fill"></i><span class="ml-2">選項</span>
                    </h3>

                    <div class="w-full flex flex-col">
                        <a href="{{ route('users.edit', ['user' => auth()->id()]) }}"
                        class="block text-black rounded-md p-2 bg-white
                        dark:text-white dark:bg-gray-600
                        {{ (request()->url() === route('users.edit', ['user' => auth()->id()])) ? 'bg-gray-200 dark:bg-gray-500' : 'hover:bg-gray-200 dark:hover:bg-gray-500' }}">
                            編輯個人資料
                        </a>

                        <a href="{{ route('users.setting', ['user' => auth()->id()]) }}"
                        class="block text-black rounded-md p-2 mt-2 bg-white
                        dark:text-white dark:bg-gray-600
                        {{ (request()->url() === route('users.setting', ['user' => auth()->id()])) ? 'bg-gray-200 dark:bg-gray-500' : 'hover:bg-gray-200 dark:hover:bg-gray-500' }}">
                            網站設定
                        </a>

                        <a href="#"
                        class="block text-black rounded-md p-2 mt-2 bg-white hover:bg-gray-200
                        dark:text-white dark:bg-gray-600 dark:hover:bg-gray-500">
                            更改密碼（開發中）
                        </a>

                        <a href="#"
                        class="block text-black rounded-md p-2 mt-2 bg-white hover:bg-gray-200
                        dark:text-white dark:bg-gray-600 dark:hover:bg-gray-500">
                            刪除帳號（開發中）
                        </a>
                    </div>
                </x-card>

            </div>

            <x-card class="w-full md:w-1/2 flex flex-col justify-center mt-6 md:mt-0">
                @yield('users.content')
            </x-card>

        </div>
    </div>
@endsection
