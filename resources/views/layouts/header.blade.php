{{-- header --}}
<nav class="navbar navbar-expand-lg navbar-light bg-light navbar-static-top">
    <div class="container">
        {{-- Branding Image --}}
        <a class="navbar-brand " href="{{ url('/') }}">
            <img src="{{ asset('icon/icon.png') }}" style="width:30px;height:30px;margin-top:-4px;margin-right:4px;">
            RECODE
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            {{-- Left Side Of Navbar --}}
            <ul class="navbar-nav mr-auto">
                <li class="nav-item @if (request()->url() == route('posts.index')) active @endif">
                    <a class="nav-link" href="{{ route('posts.index') }}"><i class="fas fa-home"></i> 所有文章</a>
                </li>
                {{-- 這裡的 $categories 使用的是 View::share() 方法取得值，寫在 AppServiceProvider.php 中 --}}
                @foreach ($categories as $category)
                    <li class="nav-item @if (request()->url() == $category->linkWithName()) active @endif">
                        <a class="nav-link" href="{{ $category->linkWithName() }}"><i class="{{ $category->icon }}"></i> {{ $category->name }}</a>
                    </li>
                @endforeach
            </ul>

            {{-- Right Side Of Navbar --}}
            <ul class="navbar-nav navbar-right">
                {{-- Authentication Links --}}
                {{-- 未登入 --}}
                @guest
                    <li class="nav-item mr-2"><a class="nav-link" href="{{ route('login') }}"><i class="fas fa-sign-in-alt"></i> 登入</a></li>
                    <a role="button" href="{{ route('register') }}" class="btn btn-outline-success">註冊</a>

                {{-- 已登入 --}}
                @else
                    <li class="nav-item">
                        <a class="nav-link mt-1 mr-3 font-weight-bold" href="{{ route('posts.create') }}">
                            <i class="fas fa-plus"></i>
                        </a>
                    </li>
                    <li class="nav-item notification-badge">
                        <a class="nav-link mr-3 badge badge-pill badge-{{ Auth::user()->notification_count > 0 ? 'hint' : 'secondary' }} text-white" href="{{ route('notifications.index') }}">
                            {{ Auth::user()->notification_count }}
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="{{ Auth::user()->gravatar() }}" class="img-responsive img-circle mr-2" width="30px" height="30px">
                            {{ Auth::user()->name }}
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('users.show', Auth::id()) }}">
                                <i class="far fa-user mr-2"></i>
                                個人頁面
                            </a>
                            <a class="dropdown-item" href="{{ route('users.edit', Auth::id()) }}">
                                <i class="far fa-edit mr-2"></i>
                                編輯資料
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" id="logout" href="#">
                                <form action="{{ route('logout') }}" method="POST" onsubmit="return confirm('您確定要登出？');">
                                    @csrf
                                    <button class="btn btn-block btn-danger" type="submit" name="button"><i class="fas fa-sign-out-alt"></i> 登出</button>
                                </form>
                            </a>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
