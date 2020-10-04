{{-- 個人頁面 --}}
@extends('layouts.app')

@section('title', $user->name . ' 的個人頁面')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-3 hidden-sm hidden-xs user-info">
                <div class="card shadow">
                    <img class="card-img-top" src="{{ $user->gravatar('500') }}" alt="{{ $user->name }}">
                    <div class="card-body">
                        <h5><strong>個人簡介</strong></h5>
                        <p>{{ $user->introduction }}</p>
                        <hr>
                        <h5><strong>註冊於</strong></h5>
                        <p>{{ $user->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                <div class="card ">
                    <div class="card-body">
                        <h1 class="mb-0" style="font-size:22px;">{{ $user->name }}</h1>
                    </div>
                </div>
                <hr>

                {{-- 會員發布的內容 --}}
                <div class="card shadow">
                    <div class="card-body">
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link bg-transparent
                                @if (!str_contains(request()->fullUrl(), 'replies'))
                                    active
                                @endif"
                                href="{{ route('users.show', $user->id) }}">
                                    文章
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link bg-transparent
                                @if (str_contains(request()->fullUrl(), 'replies'))
                                    active
                                @endif"
                                href="{{ route('users.show', [$user->id, 'tab' => 'replies']) }}">
                                    回覆
                                </a>
                            </li>
                        </ul>
                        @if (str_contains(request()->getUri(), 'replies'))
                            @include('users.replies', ['replies' => $user->replies()->with('post')->latest()->paginate(5)])
                        @else
                            @include('users.posts', ['posts' => $user->posts()->latest()->paginate(5)])
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
