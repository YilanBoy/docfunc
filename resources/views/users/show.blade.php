{{-- 個人頁面 --}}
@extends('layouts.app')

@section('title', $user->name . ' 的個人頁面')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-3 hidden-sm hidden-xs user-info">
                <div class="card shadow mb-4">
                    <img class="card-img-top" src="{{ $user->gravatar('500') }}" alt="{{ $user->name }}">
                    <div class="card-body">
                        <h2 class="mb-0">{{ $user->name }}</h2>
                        <hr>
                        <h4><strong>註冊於</strong></h4>
                        <p>{{ $user->created_at->format('Y / m / d') . '（' . $user->created_at->diffForHumans() . '）' }}</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                <div class="card ">
                    <div class="card-body">
                        <h4><strong>個人簡介</strong></h4>
                        <span>{{ $user->introduction }}</span>
                    </div>
                </div>
                <hr>

                {{-- 會員發布的內容 --}}
                <div class="card shadow mb-4">
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
                            @include('users.replies', ['replies' => $user->replies()->with('post')->latest()->paginate(10)])
                        @else
                            @include('users.posts', ['posts' => $user->posts()->latest()->paginate(10)])
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
