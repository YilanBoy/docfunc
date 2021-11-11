@section('title', $user->name . ' 的個人頁面')

{{-- 個人頁面 --}}
<x-app-layout>
    <div class="container mx-auto max-w-7xl">
        <div class="flex flex-col items-center justify-start min-h-screen
        px-4 mt-6 md:flex-row md:justify-center md:items-start xl:px-0">

            {{-- 會員基本資訊 --}}
            <x-card class="flex flex-col items-center justify-center w-full mr-0 md:w-80 md:mr-6 dark:text-gray-50">
                {{-- 大頭貼 --}}
                <div>
                    <img
                        class="rounded-full h-36 w-36"
                        src="{{ $user->gravatar('500') }}"
                        alt="{{ $user->name }}"
                        width="200"
                    >
                </div>

                {{-- 會員名稱 --}}
                <span class="flex items-center justify-center w-full pb-5 my-5 text-3xl font-semibold
                border-b-2 border-black dark:text-gray-50 dark:border-white">
                    {{ $user->name }}
                </span>

                {{-- 資訊 --}}
                <div class="flex flex-col items-start justify-start w-full dark:text-gray-50">
                    <span class="text-lg">撰寫文章</span>
                    <span class="mt-2 text-xl font-semibold">{{ $user->posts->count() }} 篇</span>

                    <span class="mt-4 text-lg">文章留言</span>
                    <span class="mt-2 text-xl font-semibold">{{ $user->comments->count() }} 次</span>

                    <span class="mt-4 text-xs">
                        註冊於 {{ $user->created_at->format('Y / m / d') . '（' . $user->created_at->diffForHumans() . '）' }}
                    </span>
                </div>
            </x-card>

            {{-- 會員資訊、文章與留言 --}}
            <div
                x-data="{ tab: 'information' }"
                class="w-full mt-4 space-y-6 xl:w-2/3 md:mt-0"
            >
                {{-- 切換顯示選單 --}}
                <nav class="flex w-full p-1 space-x-1 md:w-4/5 lg:w-1/2
                rounded-xl bg-gray-400/30 dark:bg-white/30 dark:text-gray-50">
                    <a
                        x-on:click.prevent="tab = 'information'"
                        href="#"
                        :class="{
                            'bg-gray-50 dark:bg-gray-600': tab === 'information',
                            'hover:bg-gray-50 dark:hover:bg-gray-600': tab !== 'information'
                        }"
                        class="flex justify-center w-1/3 px-4 py-2 transition duration-300 rounded-lg"
                    >會員資訊</a>
                    <a
                        x-on:click.prevent="tab = 'posts'"
                        href="#"
                        :class="{
                            'bg-gray-50 dark:bg-gray-600': tab === 'posts',
                            'hover:bg-gray-50 dark:hover:bg-gray-600': tab !== 'posts'
                        }"
                        class="flex justify-center w-1/3 px-4 py-2 transition duration-300 rounded-lg"
                    >發布文章</a>
                    <a
                        x-on:click.prevent="tab = 'comments'"
                        href="#"
                        :class="{
                            'bg-gray-50 dark:bg-gray-600': tab === 'comments',
                            'hover:bg-gray-50 dark:hover:bg-gray-600': tab !== 'comments'
                        }"
                        class="flex justify-center w-1/3 px-4 py-2 transition duration-300 rounded-lg"
                    >留言紀錄</a>
                </nav>

                {{-- 會員資訊 --}}
                <div
                    x-cloak
                    x-show="tab === 'information'"
                    x-transition:enter.duration.300ms
                >
                    @include('users.information')
                </div>

                {{-- 會員文章 --}}
                <div
                    x-cloak
                    x-show="tab === 'posts'"
                    x-transition:enter.duration.300ms
                >
                    <livewire:user.posts :user="$user"/>
                </div>

                {{-- 會員留言 --}}
                <div
                    x-cloak
                    x-show="tab === 'comments'"
                    x-transition:enter.duration.300ms
                >
                    <livewire:user.comments :user="$user"/>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
