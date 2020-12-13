{{-- 通知列表 --}}
@extends('layouts.app')

@section('title', '我的通知')

@section('content')
<div class="container mb-5">
    <div class="row justify-content-md-center">
        <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">

                <div class="card shadow">
                    <h3 class="card-header py-3"><i class="far fa-bell" aria-hidden="true"></i> 我的通知</h3>
                    <div class="card-body">
                        @if ($notifications->count())
                            <ul class="list-group list-group-flush">
                                @foreach ($notifications as $notification)
                                    @include('notifications.list')
                                @endforeach

                                {{ $notifications->links() }}
                            </ul>
                        @else
                            <div class="d-flex justify-content-center p-5">
                                <h4>沒有消息通知！</h4>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
@stop
