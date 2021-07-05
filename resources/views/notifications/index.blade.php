{{-- 通知列表 --}}
@extends('layouts.app')

@section('title', '我的通知')

@section('content')
    <div class="container mx-auto max-w-7xl mt-6">
        <div class="flex justify-center items-center px-4 xl:px-0">

            <div class="w-full md:w-2/3 xl:w-1/2 space-y-6 flex flex-col justify-center items-center">
                {{-- Notification Title --}}
                <div class="fill-current text-gray-700 text-2xl">
                    <i class="bi bi-bell-fill"></i><span class="ml-4">我的通知</span>
                </div>

                {{-- Notification Cards --}}
                @forelse ($notifications as $notification)
                    <div
                        x-data
                        x-on:click="
                            const clicked = $event.target
                            const target = clicked.tagName.toLowerCase()
                            const ignores = ['a']

                            if (!ignores.includes(target)) {
                                clicked.closest('.notifications-container').querySelector('.notification-link').click()
                            }
                        "
                        class="notifications-container flex flex-col md:flex-row justify-between p-4 shadow-md hover:shadow-xl bg-white rounded-xl
                        transform hover:-translate-x-2 transition duration-150 ease-in cursor-pointer ring-1 ring-black ring-opacity-20"
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
                                    class="text-gray-400 hover:text-gray-700"
                                >{{ $notification->data['user_name'] }}</a>
                                回覆
                                <a class="notification-link text-gray-400 hover:text-gray-700"
                                href="{{ $notification->data['post_link'] }}">{{ $notification->data['post_title'] }}</a>
                            </div>

                            <div class="text-gray-600 mt-2">
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

                    </div>

                @empty
                    <div class="transform hover:-translate-x-2 transition duration-150 ease-in shadow-md hover:shadow-xl bg-white rounded-xl
                    flex justify-center items-center cursor-pointer ring-1 ring-black ring-opacity-20 w-full h-24 p-4">
                        <span>沒有消息通知！</span>
                    </div>
                @endforelse

                <div>
                    {{ $notifications->links() }}
                </div>
            </div>

        </div>
    </div>
@endsection
