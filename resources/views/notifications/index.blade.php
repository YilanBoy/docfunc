{{-- 通知列表 --}}
@extends('layouts.app')

@section('title', '我的通知')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow">

                    <div class="card-body">

                        <h3 class="text-xs-center">
                            <i class="far fa-bell" aria-hidden="true"></i> 我的通知
                        </h3>
                        <hr>

                        @if ($notifications->count())
                            <div class="list-unstyled notification-list">
                                @foreach ($notifications as $notification)
                                    {{-- @include('notifications.types._' . Str::snake(class_basename($notification->type))) --}}
                                    @include('notifications.list')
                                @endforeach

                                {{ $notifications->links() }}
                            </div>
                        @else
                            <div class="d-flex justify-content-center p-5">沒有消息通知！</div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
