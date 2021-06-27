{{-- header --}}
<nav class="navbar navbar-expand-lg navbar-light bg-light py-3 mb-5 border-top border-info border-5 shadow" id="header">
    <div class="container">
        {{-- Branding Image --}}
        <a class="navbar-brand" href="{{ url('/') }}">
            <img src="{{ asset('icon/icon.png') }}"
            style="margin-top: -4px;margin-right: 4px;"
            width="30px" height="30px">
            <span class="font-monospace fw-bold">{{ config('app.name') }}</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-2 mb-2 mb-lg-0">
                <li class="nav-item">
                    <a href="{{ route('posts.index') }}"
                    @if (request()->url() === route('posts.index'))
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
                        <a href="{{ $category->link_with_name }}"
                        @if (request()->url() === $category->link_with_name)
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

            {{-- 搜尋 --}}
            <div class="aa-input-container me-auto" id="aa-input-container">
                <input type="search" id="aa-search-input" class="aa-input-search"
                placeholder="搜尋文章" name="search" autocomplete="off" />

                <svg class="aa-input-icon" viewBox="654 -372 1664 1664">
                    <path d="M1806,332c0-123.3-43.8-228.8-131.5-316.5C1586.8-72.2,1481.3-116,1358-116s-228.8,43.8-316.5,131.5
                    C953.8,103.2,910,208.7,910,332s43.8,228.8,131.5,316.5C1129.2,736.2,1234.7,780,1358,780s228.8-43.8,316.5-131.5
                    C1762.2,560.8,1806,455.3,1806,332z M2318,1164c0,34.7-12.7,64.7-38,90s-55.3,38-90,38c-36,0-66-12.7-90-38l-343-342
                    c-119.3,82.7-252.3,124-399,124c-95.3,0-186.5-18.5-273.5-55.5s-162-87-225-150s-113-138-150-225S654,427.3,654,332
                    s18.5-186.5,55.5-273.5s87-162,150-225s138-113,225-150S1262.7-372,1358-372s186.5,18.5,273.5,55.5s162,87,225,150s113,138,150,225
                    S2062,236.7,2062,332c0,146.7-41.3,279.7-124,399l343,343C2305.7,1098.7,2318,1128.7,2318,1164z" />
                </svg>
            </div>

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
                    @if (request()->url() !== route('posts.create'))
                        <li class="nav-item d-flex justify-content-start align-items-center me-2">
                            <a class="nav-link" href="{{ route('posts.create') }}" title="新增文章">
                                <i class="fas fa-plus"></i>
                            </a>
                        </li>
                    @endif

                    {{-- 通知顯示 --}}
                    <li class="nav-item d-flex justify-content-start align-items-center me-2">
                        <a class="nav-link" href="{{ route('notifications.index') }}">
                            <span class="btn {{ auth()->user()->notification_count > 0 ? 'btn-danger' : 'btn-secondary' }} btn-sm rounded-pill py-0">
                                {{ auth()->user()->notification_count }}
                            </span>
                        </a>
                    </li>

                    {{-- 會員大頭貼 --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{ auth()->user()->gravatar() }}" class="rounded-circle me-2" width="30px" height="30px">
                            {{ auth()->user()->name }}
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li>
                                <a class="dropdown-item" href="{{ route('users.show', ['user' => auth()->id()]) }}">
                                    <i class="far fa-user me-2"></i>個人頁面
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('users.edit', ['user' => auth()->id()]) }}">
                                    <i class="far fa-edit me-2"></i>編輯資料
                                </a>
                            </li>

                            <div class="dropdown-divider"></div>

                            <li>
                                <a class="dropdown-item" href="#">
                                    <div class="d-flex justify-content-center">
                                        <form action="{{ route('logout') }}" method="POST" onsubmit="return confirm('您確定要登出？');">
                                            @csrf
                                            <button class="btn btn-danger shadow" type="submit" name="button"><i class="fas fa-sign-out-alt"></i> 登出</button>
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
