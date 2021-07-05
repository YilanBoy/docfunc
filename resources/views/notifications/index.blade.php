{{-- 通知列表 --}}
@extends('layouts.app')

@section('title', '我的通知')

@section('content')
    <div class="container mx-auto max-w-7xl mt-6">
        <div class="flex flex-col space-y-6 xl:space-y-0 xl:flex-row justify-center items-center px-4 xl:px-0">

            <div class="w-full md:w-2/3 lg:w-1/2">
                <div class="space-y-6 mb-6">

                    {{-- Notification Title --}}
                    <div class="flex items-center">
                        <div class="text-gray-700 font-semibold px-7 border-b-4 border-blue-500 pb-2">
                            <i class="bi bi-bell-fill"></i><span class="ml-2">我的通知</span>
                        </div>
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
                                    clicked.closest('.notification-container').querySelector('.notification-link').click()
                                }
                            "
                            class="notification-container transform hover:-translate-x-2 transition duration-150 ease-in shadow-md
                            hover:shadow-xl bg-white rounded-xl cursor-pointer ring-1 ring-black ring-opacity-20"
                        >
                            <div class="flex flex-col md:flex-row justify-between p-4">
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
                                    <div class="mt-4 text-sm text-gray-600">
                                        <i class="bi bi-clock-fill"></i>
                                        <span class="ml-2" title="{{ $notification->created_at }}">
                                            {{ $notification->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>

                            </div>
                        </div>

                    @empty
                        <div
                            class="notification-container transform hover:-translate-x-2 transition duration-150 ease-in shadow-md hover:shadow-xl bg-white rounded-xl
                            flex justify-center items-center cursor-pointer ring-1 ring-black ring-opacity-20 h-24"
                        >
                            <span>沒有消息通知！</span>
                        </div>
                    @endforelse
                </div>

                <div>
                    {{ $notifications->links() }}
                </div>
            </div>
        </div>
    </div>
    {{-- <div class="container mb-5">
        <div class="row justify-content-md-center">
            <div class="col-12 col-xl-8">

                <div class="card shadow">
                    <h5 class="card-header py-3"><i class="far fa-bell" aria-hidden="true"></i> 我的通知</h5>
                    <div class="card-body">
                        @if ($notifications->count())
                            <ul class="list-group list-group-flush">
                                @foreach ($notifications as $notification)
                                    <!-- 通知列表區塊 -->
                                    <li class="list-group-item px-0">
                                        <div class="row p-2">
                                            <!-- 大頭貼 -->
                                            <div class="col-2 d-flex justify-content-center align-items-center">
                                                <a href="{{ route('users.show', ['user' => $notification->data['user_id']]) }}">
                                                    <img class="rounded-circle"
                                                    alt="{{ $notification->data['user_name'] }}"
                                                    src="{{ $notification->data['user_avatar'] }}"
                                                    width="48px" height="48px">
                                                </a>
                                            </div>

                                            <!-- 回覆內容 -->
                                            <div class="col-8">
                                                <div class="mb-1">
                                                    <a class="text-decoration-none"
                                                    href="{{ route('users.show', ['user' => $notification->data['user_id']]) }}">{{ $notification->data['user_name'] }}</a>
                                                    回覆
                                                    <a class="text-decoration-none"
                                                    href="{{ $notification->data['post_link'] }}">{{ $notification->data['post_title'] }}</a>
                                                </div>
                                                <div class="card-text">
                                                    {!! $notification->data['reply_content'] !!}
                                                </div>
                                            </div>

                                            <!-- 回覆時間 -->
                                            <div class="">
                                                <span title="{{ $notification->created_at }}">
                                                    <i class="bi bi-clock-fill"></i>
                                                    <span class="ml-2">
                                                        {{ $notification->created_at->diffForHumans() }}
                                                    </span>
                                                </span>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach

                                {{ $notifications->links() }}
                            </ul>
                        @else
                            <div class="d-flex justify-content-center p-5">
                                <label class="fs-3">沒有消息通知！</label>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div> --}}
@endsection
