{{-- header --}}
<nav class="navbar navbar-expand-lg navbar-light bg-light py-3 mb-5 border-top border-info border-5 shadow">
    <div class="container">
        {{-- Branding Image --}}
        <a class="navbar-brand" href="{{ url('/') }}">
            <img src="{{ asset('icon/icon.png') }}"
            style="margin-top: -4px;margin-right: 4px;"
            width="30px" height="30px">
            {{ config('app.name') }}
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a href="{{ route('posts.index') }}"
                    @if (request()->url() == route('posts.index'))
                        class="nav-link active"  aria-current="page"
                    @else
                        class="nav-link"
                    @endif
                    >
                        <i class="fas fa-home"></i> 所有文章
                    </a>
                </li>
                {{-- 這裡的 $categories 使用的是 View::share() 方法取得值，寫在 AppServiceProvider.php 中 --}}
                @foreach ($categories as $category)
                    <li class="nav-item">
                        <a href="{{ $category->linkWithName() }}"
                        @if (request()->url() == $category->linkWithName())
                            class="nav-link active"  aria-current="page"
                        @else
                            class="nav-link"
                        @endif
                        >
                            <i class="{{ $category->icon }}"></i> {{ $category->name }}
                        </a>
                    </li>
                @endforeach
            </ul>

            <ul class="navbar-nav mb-2 mb-lg-0">
                {{-- 未登入 --}}
                @guest
                    <li class="nav-item me-2">
                        <a class="nav-link" href="{{ route('posts.create') }}">
                            <i class="fas fa-sign-in-alt"></i> 登入
                        </a>
                    </li>
                    <li class="nav-item">
                        <a role="button" href="{{ route('register') }}" class="btn btn-outline-success">註冊</a>
                    </li>
                {{-- 已登入 --}}
                @else
                    {{-- 新增文章 --}}
                    <li class="nav-item d-flex justify-content-start align-items-center me-2">
                        <a class="nav-link" href="{{ route('posts.create') }}">
                            <i class="fas fa-plus"></i>
                        </a>
                    </li>

                    {{-- 通知顯示 --}}
                    <li class="nav-item d-flex justify-content-start align-items-center me-2">
                        <a class="nav-link" href="{{ route('notifications.index') }}">
                            <span class="btn btn-{{ Auth::user()->notification_count > 0 ? 'danger' : 'secondary' }} btn-sm rounded-pill py-0">
                                {{ Auth::user()->notification_count }}
                            </span>
                        </a>
                    </li>

                    {{-- 會員大頭貼 --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{ Auth::user()->gravatar() }}" class="rounded-circle me-2" width="30px" height="30px">
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li>
                                <a class="dropdown-item" href="{{ route('users.show', Auth::id()) }}">
                                    <i class="far fa-user me-2"></i>個人頁面
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('users.edit', Auth::id()) }}">
                                    <i class="far fa-edit me-2"></i>編輯資料
                                </a>
                            </li>

                            <div class="dropdown-divider"></div>

                            <li>
                                <a class="dropdown-item" href="#">
                                    <div class="d-flex justify-content-center">
                                        <form action="{{ route('logout') }}" method="POST" onsubmit="return confirm('您確定要登出？');">
                                            @csrf
                                            <button class="btn btn-danger" type="submit" name="button"><i class="fas fa-sign-out-alt"></i> 登出</button>
                                        </form>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
