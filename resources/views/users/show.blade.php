{{-- 個人頁面 --}}
@extends('layouts.app')

@section('title', $user->name . ' 的個人頁面')

@section('content')
    <div class="container mb-5">
        <div class="row justify-content-md-center g-4">

            <div class="col-12 col-md-3">
                <div class="card shadow mb-4">
                    <img class="card-img-top" src="{{ $user->gravatar('500') }}" alt="{{ $user->name }}">
                    <div class="card-body">
                        <label class="fs-3 mb-0">{{ $user->name }}</label>

                        <hr>

                        <label>撰寫文章</label>
                        <p class="fs-4 fw-bold">{{ $user->posts->count() }} 篇</p>

                        <label>文章留言</label>
                        <p class="fs-4 fw-bold">{{ $user->replies->count() }} 次</p>

                        <label>註冊於 {{ $user->created_at->format('Y / m / d') . '（' . $user->created_at->diffForHumans() . '）' }}</label>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-8">
                <div class="card p-3 mb-4">
                    <div class="card-body">
                        <h5>個人簡介</h5>
                        <hr>
                        @if ($user->introduction)
                            <p class="card-text">{!! nl2br(e($user->introduction)) !!}</p>
                        @else
                            <p class="card-text">目前尚無個人簡介～</p>
                        @endif
                    </div>
                </div>

                {{-- 會員發布的內容 --}}
                <div class="card shadow mb-4">

                    <div class="card-header">
                        <ul class="nav nav-tabs card-header-tabs">
                            <li class="nav-item">
                                <a href="{{ route('users.show', ['user' => $user->id]) }}"
                                @if (!str_contains(request()->fullUrl(), 'replies'))
                                    class="nav-link active" aria-current="true"
                                @else
                                    class="nav-link link-secondary"
                                @endif
                                >
                                    <span class="fs-5">發布文章</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('users.show', ['user' => $user->id, 'tab' => 'replies']) }}"
                                @if (str_contains(request()->fullUrl(), 'replies'))
                                    class="nav-link active" aria-current="true"
                                @else
                                    class="nav-link link-secondary"
                                @endif
                                >
                                    <span class="fs-5">回覆紀錄</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body">
                        @if (str_contains(request()->getUri(), 'replies'))
                            @include('users.replies', ['replies' => $replies])
                        @else
                            @include('users.posts', ['posts' => $posts])
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
