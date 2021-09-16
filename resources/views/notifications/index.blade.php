{{-- 通知列表 --}}
@extends('layouts.app')

@section('title', '我的通知')

@section('content')
    <div class="container mx-auto max-w-7xl">
        <div class="min-h-screen flex justify-center items-center px-4 xl:px-0 mt-6">

            <div class="w-full md:w-2/3 xl:w-1/2 space-y-6 flex flex-col justify-center items-center">
                {{-- 頁面標題 --}}
                <div class="fill-current text-gray-700 text-2xl dark:text-gray-50">
                    <i class="bi bi-bell-fill"></i><span class="ml-4">我的通知</span>
                </div>

                {{-- 通知列表 --}}
                @forelse ($notifications as $notification)
                    <x-card
                        x-data="cardLink"
                        x-on:click="notificationCardLink($event, $refs)"
                        class="w-full flex flex-col md:flex-row justify-between hover:shadow-xl
                        transform hover:-translate-x-1 transition duration-150 ease-in cursor-pointer"
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
                                    class="text-gray-400 hover:text-gray-700 dark:hover:text-gray-50"
                                >{{ $notification->data['user_name'] }}</a>
                                <span class="dark:text-gray-50">留言了你的文章</span>
                                <a
                                    x-ref="notificationLink"
                                    class="text-gray-400 hover:text-gray-700 dark:hover:text-gray-50"
                                    href="{{ $notification->data['post_link'] }}"
                                >
                                    {{'「' . $notification->data['post_title'] . '」' }}
                                </a>
                            </div>

                            <div class="text-gray-600 mt-2 dark:text-gray-50">
                                {!! $notification->data['comment_content'] !!}
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
                    transform hover:-translate-x-1 transition duration-150 ease-in cursor-pointer
                    dark:text-gray-50">
                        <span>沒有消息通知！</span>
                    </x-card>
                @endforelse

                <div>
                    {{ $notifications->links() }}
                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('cardLink', () => ({
                // 通知連結
                notificationCardLink(event, refs) {
                    let ignores = ['a'];
                    let targetTagName = event.target.tagName.toLowerCase();

                    if (!ignores.includes(targetTagName)) {
                        refs.notificationLink.click();
                    }
                }
            }));
        });
    </script>
@endsection
