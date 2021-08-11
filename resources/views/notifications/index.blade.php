{{-- 通知列表 --}}
@extends('layouts.app')

@section('title', '我的通知')

@section('content')
    <div class="container mx-auto max-w-7xl">
        <div class="min-h-screen flex justify-center items-center px-4 xl:px-0 mt-6">

            <div class="w-full md:w-2/3 xl:w-1/2 space-y-6 flex flex-col justify-center items-center">
                {{-- Title --}}
                <div class="fill-current text-gray-700 text-2xl dark:text-white">
                    <i class="bi bi-bell-fill"></i><span class="ml-4">我的通知</span>
                </div>

                {{-- Notification --}}
                @forelse ($notifications as $notification)
                    <x-card
                        x-data="{}"
                        x-on:click="
                            const targetTagName = $event.target.tagName.toLowerCase()
                            const ignores = ['a']

                            if (!ignores.includes(targetTagName)) {
                                $refs.notificationLink.click()
                            }
                        "
                        class="w-full flex flex-col md:flex-row justify-between hover:shadow-xl
                        transform hover:-translate-x-2 transition duration-150 ease-in cursor-pointer"
                    >
                        {{-- 大頭貼 --}}
                        <div class="flex-none">
                            <a href="{{ route('users.show', ['user' => $notification->data['user_id']]) }}">
                                <img
                                alt="{{ $notification->data['user_name'] }}"
                                src="{{ $notification->data['user_avatar'] }}"
                                class="w-14 h-14 rounded-xl hover:ring-4 hover:ring-blue-400">
                            </a>
                        </div>

                        {{-- 通知內容 --}}
                        <div class="w-full flex flex-col justify-between md:mx-4">
                            {{-- 文章標題 --}}
                            <div class="mt-2 md:mt-0">
                                <a
                                    href="{{ route('users.show', ['user' => $notification->data['user_id']]) }}"
                                    class="text-gray-400 hover:text-gray-700 dark:hover:text-white"
                                >{{ $notification->data['user_name'] }}</a>
                                <span class="text-black dark:text-white">回覆了你的文章</span>
                                <a
                                    x-ref="notificationLink"
                                    class="text-gray-400 hover:text-gray-700 dark:hover:text-white"
                                    href="{{ $notification->data['post_link'] }}"
                                >
                                    {{'「' . $notification->data['post_title'] . '」' }}
                                </a>
                            </div>

                            <div class="text-gray-600 mt-2 dark:text-white">
                                {!! $notification->data['reply_content'] !!}
                            </div>

                            {{-- 通知時間 --}}
                            <div class="mt-4 text-sm text-gray-400">
                                <i class="bi bi-clock-fill"></i>
                                <span class="ml-2" title="{{ $notification->created_at }}">
                                    {{ $notification->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>

                    </x-card>

                @empty
                    <x-card class="w-full h-24 flex justify-center items-center hover:shadow-xl
                    transform hover:-translate-x-2 transition duration-150 ease-in cursor-pointer
                    dark:text-white">
                        <span>沒有消息通知！</span>
                    </x-card>
                @endforelse

                <div>
                    {{ $notifications->links() }}
                </div>
            </div>

        </div>
    </div>
@endsection
