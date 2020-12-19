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
                        <h3 class="mb-0">{{ $user->name }}</h3>
                        <hr>
                        <h5>註冊於</h5>
                        <p>{{ $user->created_at->format('Y / m / d') . '（' . $user->created_at->diffForHumans() . '）' }}</p>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-9">
                <div class="card p-3">
                    <div class="card-body">
                        <h4>個人簡介</h4>
                        <hr>
                        @if ($user->introduction)
                            <p class="card-text">{{ $user->introduction }}</p>
                        @else
                            <p class="card-text">目前尚無個人簡介～</p>
                        @endif
                    </div>
                </div>

                <hr>

                {{-- 會員發布的內容 --}}
                <div class="card shadow mb-4">

                    <div class="card-header">
                        <ul class="nav nav-tabs card-header-tabs">
                            <li class="nav-item">
                                <a href="{{ route('users.show', $user->id) }}"
                                @if (!str_contains(request()->fullUrl(), 'replies'))
                                    class="nav-link active" aria-current="true"
                                @else
                                    class="nav-link link-secondary"
                                @endif
                                >
                                    <span class="fs-5">文章</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('users.show', [$user->id, 'tab' => 'replies']) }}"
                                @if (str_contains(request()->fullUrl(), 'replies'))
                                    class="nav-link active" aria-current="true"
                                @else
                                    class="nav-link link-secondary"
                                @endif
                                >
                                    <span class="fs-5">回覆</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body">
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
