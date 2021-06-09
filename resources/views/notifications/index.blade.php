{{-- 通知列表 --}}
@extends('layouts.app')

@section('title', '我的通知')

@section('content')
    <div class="container mb-5">
        <div class="row justify-content-md-center">
            <div class="col-12 col-xl-8">

                <div class="card shadow">
                    <h5 class="card-header py-3"><i class="far fa-bell" aria-hidden="true"></i> 我的通知</h5>
                    <div class="card-body">
                        @if ($notifications->count())
                            <ul class="list-group list-group-flush">
                                @foreach ($notifications as $notification)
                                    {{-- 通知列表區塊 --}}
                                    <li class="list-group-item px-0">
                                        <div class="row p-2">
                                            {{-- 大頭貼 --}}
                                            <div class="col-2 d-flex justify-content-center align-items-center">
                                                <a href="{{ route('users.show', ['user' => $notification->data['user_id']]) }}">
                                                    <img class="rounded-circle"
                                                    alt="{{ $notification->data['user_name'] }}"
                                                    src="{{ $notification->data['user_avatar'] }}"
                                                    width="48px" height="48px">
                                                </a>
                                            </div>

                                            {{-- 回覆內容 --}}
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

                                            {{-- 回覆時間 --}}
                                            <div class="col-2 d-flex justify-content-center align-items-center">
                                                <span title="{{ $notification->created_at }}">
                                                    <i class="far fa-clock"></i>
                                                    {{ $notification->created_at->diffForHumans() }}
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
    </div>
@stop
