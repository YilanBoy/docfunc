{{-- Header --}}
<nav id="header">
    {{-- 手機版選單 --}}
    <div
        x-data="{ mobileMenuIsOpen: false }"
        class="relative z-20 bg-white shadow-md lg:hidden
        dark:bg-gray-800 "
    >
        <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
            <div class="relative flex items-center justify-between h-18">
                <div
                    class="absolute inset-y-0 left-0 flex items-center"
                >
                    {{-- 手機版-開關選單按鈕 --}}
                    <button
                        x-on:click="mobileMenuIsOpen = ! mobileMenuIsOpen"
                        type="button"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-700"
                        aria-controls="mobile-menu"
                        aria-expanded="false"
                    >
                        <span class="sr-only">Open main menu</span>
                            {{-- 手機版-關閉選單的 icon --}}
                        <div
                            :class="mobileMenuIsOpen ? 'hidden' : 'block'"
                            class="text-3xl text-gray-400 hover:text-gray-700"
                        >
                            <i class="bi bi-list"></i>
                        </div>
                        {{-- 手機版-開啟選單的 icon --}}
                        <div
                            x-cloak
                            :class="mobileMenuIsOpen ? 'block' : 'hidden'"
                            class="text-xl text-gray-400 hover:text-gray-700"
                        >
                            <i class="bi bi-x-lg"></i>
                        </div>
                    </button>
                </div>

                <div class="mx-auto flex items-center">
                    <img class="block h-8 w-auto" src="{{ asset('images/icon/icon.svg') }}" alt="{{ config('app.name') }}">
                    <span
                        class="hidden md:block font-bold font-mono text-xl ml-2
                        dark:text-white"
                    >
                        {{ config('app.name') }}
                    </span>
                </div>

                <div class="absolute inset-y-0 right-0 flex items-center">
                    {{-- 明亮 / 暗黑模式切換 --}}
                    <button type="button" class="theme-switch mr-3 text-gray-400 hover:text-gray-700 dark:hover:text-white">
                        <span class="dark:hidden">
                            <i class="bi bi-sun-fill"></i>
                        </span>

                        <span class="hidden dark:inline">
                            <i class="bi bi-moon-stars-fill"></i>
                        </span>
                    </button>

                    {{-- 手機版-未登入 --}}
                    @guest
                        <a href="{{ route('register') }}"
                        class="bg-transparent hover:bg-blue-500 text-blue-700 hover:text-white py-2 px-4 mr-3
                        border border-blue-500 hover:border-transparent rounded-md">
                            註冊
                        </a>

                        <a href="{{ route('login') }}"
                        class="text-gray-400 hover:text-gray-700 dark:hover:text-white">
                            <i class="bi bi-box-arrow-in-right"></i><span class="ml-2">登入</span>
                        </a>

                    {{-- 手機版-已登入 --}}
                    @else
                        {{-- 手機版-通知 --}}
                        <a
                            href="{{ route('notifications.index') }}"
                            @class([
                                'p-1 mr-3 rounded-full',
                                'text-red-400 hover:text-red-700' => (auth()->user()->notification_count > 0),
                                'text-gray-400 hover:text-gray-700 dark:hover:text-white' => (auth()->user()->notification_count === 0),
                            ])
                        >
                            <span class="sr-only">View notifications</span>
                            <i class="bi bi-bell-fill"></i>
                        </a>

                        {{-- 手機版-會員選單 --}}
                        <div
                            x-data="{ profileIsOpen: false }"
                            class="relative"
                        >
                            {{-- 手機版-會員大頭貼 --}}
                            <div>
                                <button
                                    x-on:click="profileIsOpen = ! profileIsOpen"
                                    x-on:keydown.escape.window="profileIsOpen = false"
                                    type="button"
                                    class="bg-gray-800 flex text-sm rounded-full
                                    focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-blue-400 focus:ring-white"
                                    id="user-menu-button" aria-expanded="false" aria-haspopup="true"
                                >
                                    <span class="sr-only">Open user menu</span>
                                    <img class=" h-10 w-10 rounded-full" src="{{ auth()->user()->gravatar() }}" alt="">
                                </button>
                            </div>

                            {{-- 手機版-會員下拉式選單 --}}
                            <div
                                x-cloak
                                x-on:click.outside="profileIsOpen = false"
                                x-show="profileIsOpen"
                                x-transition.origin.top.right
                                class="absolute right-0 p-2 mt-2 w-48 rounded-md shadow-lg bg-white text-gray-700 ring-1 ring-black ring-opacity-20
                                dark:bg-gray-500 dark:text-white"
                                role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1"
                            >
                                <a href="{{ route('users.show', ['user' => auth()->id()]) }}"
                                role="menuitem" tabindex="-1"
                                class="block px-4 py-2 rounded-md hover:bg-gray-200 dark:hover:bg-gray-400">
                                    <i class="bi bi-person-fill"></i><span class="ml-2">個人頁面</span>
                                </a>

                                <a href="{{ route('users.edit', ['user' => auth()->id()]) }}"
                                role="menuitem" tabindex="-1"
                                class="block px-4 py-2 rounded-md hover:bg-gray-200 dark:hover:bg-gray-400">
                                    <i class="bi bi-person-circle"></i><span class="ml-2">會員中心</span>
                                </a>

                                <form id="logout" action="{{ route('logout') }}" method="POST" onSubmit="return confirm('您確定要登出？');"
                                class="hidden"
                                >
                                    @csrf
                                </form>

                                <button
                                    type="submit" form="logout"
                                    role="menuitem" tabindex="-1"
                                    class="flex items-start w-full px-4 py-2 rounded-md hover:bg-gray-200 dark:hover:bg-gray-400"
                                >
                                    <i class="bi bi-box-arrow-left"></i><span class="ml-2">登出</span>
                                </button>
                            </div>
                        </div>
                    @endguest
                </div>
            </div>
        </div>

        {{-- 手機版-分類下拉式選單 --}}
        <div
            x-cloak
            x-show="mobileMenuIsOpen"
            x-transition.origin.top
            class="lg:hidden"
        >
            <div class="px-2 pt-2 pb-3 space-y-1">
                {{-- 手機版-新增文章 --}}
                <a
                    href="{{ route('posts.create') }}"
                    @class([
                        'block px-3 py-2 rounded-md font-medium',
                        'bg-gray-200 text-gray-700' => (request()->url() === route('posts.create')),
                        'text-gray-400 hover:text-gray-700 hover:bg-gray-200' => (request()->url() !== route('posts.create')),
                    ])
                    @if(request()->url() === route('posts.create')) aria-current="page" @endif
                >
                    <i class="bi bi-pencil-fill"></i><span class="ml-2">新增文章</span>
                </a>

                <a
                    href="{{ route('posts.index') }}"
                    @class([
                        'block px-3 py-2 rounded-md font-medium',
                        'bg-gray-200 text-gray-700' => (request()->url() === route('posts.index')),
                        'text-gray-400 hover:text-gray-700 hover:bg-gray-200' => (request()->url() !== route('posts.index')),
                    ])
                    @if(request()->url() === route('posts.index')) aria-current="page" @endif
                >
                    <i class="bi bi-house-fill"></i><span class="ml-2">全部文章</span>
                </a>
                @foreach ($categories as $category)
                    <a
                        href="{{ $category->link_with_name }}"
                        @class([
                            'block px-3 py-2 rounded-md font-medium',
                            'bg-gray-200 text-gray-700' => (request()->url() === $category->link_with_name),
                            'text-gray-400 hover:text-gray-700 hover:bg-gray-200' => (request()->url() !== $category->link_with_name),
                        ])
                        @if(request()->url() === $category->link_with_name) aria-current="page" @endif
                    >
                        <i class="{{ $category->icon }}"></i><span class="ml-2">{{ $category->name }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    {{-- 電腦版選單 --}}
    <div
        class="hidden z-20 w-16 h-screen lg:flex flex-col justify-between items-center fixed top-0 left-0
        bg-white shadow-md transition-all duration-300 py-2
        dark:bg-gray-900"
    >
        {{-- 電腦版-Logo --}}
        <div
            class="relative"
        >
            <a href="{{ route('root') }}" class="peer w-14 h-14 flex justify-center items-center">
                <img class="w-10" src="{{ asset('images/icon/icon.svg') }}" alt="{{ config('app.name') }}">
            </a>

            <span
                class="absolute top-5 left-16 flex justify-center items-center w-max opacity-0 transition-all duration-300
                text-gray-900 text-2xl font-bold font-mono bg-white rounded-lg ring-1 ring-black ring-opacity-20 px-6 py-2 pointer-events-none
                peer-hover:opacity-100 peer-hover:top-1
                dark:bg-gray-500 dark:text-white"
            >
                {{ config('app.name') }}
            </span>
        </div>

        <ul class="mt-6 space-y-2">
            {{-- 電腦版-搜尋 --}}
            <li>
                @livewire('search')
            </li>

            <li>
                {{-- 電腦版-新增文章 --}}
                <x-sidebar-link
                    href="{{ route('posts.create') }}"
                    class="{{
                        (request()->url() === route('posts.create')) ?
                        'bg-gray-200 text-gray-700' :
                        'text-gray-400 hover:text-gray-700 hover:bg-gray-200'
                    }}"
                    :icon="'bi bi-pencil-fill'"
                >
                    新增文章
                </x-sidebar-link>
            </li>

            <li>
                <x-sidebar-link
                    href="{{ route('posts.index') }}"
                    class="{{
                        (request()->url() === route('posts.index')) ?
                        'bg-gray-200 text-gray-700' :
                        'text-gray-400 hover:text-gray-700 hover:bg-gray-200'
                    }}"
                    :icon="'bi bi-house-fill'"
                >
                    全部文章
                </x-sidebar-link>
            </li>

            @foreach ($categories as $category)
                <li>
                    <x-sidebar-link
                        href="{{ $category->link_with_name }}"
                        class="{{
                            (request()->url() === $category->link_with_name) ?
                            'bg-gray-200 text-gray-700' :
                            'text-gray-400 hover:text-gray-700 hover:bg-gray-200'
                        }}"
                        :icon="$category->icon"
                    >
                        {{ $category->name }}
                    </x-sidebar-link>
                </li>
            @endforeach
        </ul>

        <div class="flex flex-col space-y-2">

            {{-- 明亮 / 暗黑模式切換 --}}
            <div class="relative">
                <button
                    type="button"
                    class="theme-switch peer h-10 w-10 flex justify-center items-center text-2xl rounded-lg transition duration-150
                    text-gray-400 hover:text-gray-700 hover:bg-gray-200"
                >
                    <span class="dark:hidden">
                        <i class="bi bi-sun-fill"></i>
                    </span>

                    <span class="hidden dark:inline">
                        <i class="bi bi-moon-stars-fill"></i>
                    </span>
                </button>

                <span
                    class="absolute left-16 -top-4 flex justify-center items-center w-max opacity-0 transition-all duration-300
                    text-gray-900 bg-white rounded-md ring-1 ring-black ring-opacity-20 px-4 py-2 pointer-events-none
                    peer-hover:opacity-100 peer-hover:top-0
                    dark:bg-gray-500 dark:text-white"
                >
                    明亮 / 暗黑模式
                </span>
            </div>

            {{-- 電腦版-未登入 --}}
            @guest
                <x-sidebar-link
                    href="{{ route('register') }}"
                    class="text-gray-400 hover:text-gray-700 hover:bg-gray-200"
                    :icon="'bi bi-person-plus-fill'"
                >
                    註冊
                </x-sidebar-link>

                <x-sidebar-link
                    href="{{ route('login') }}"
                    class="text-gray-400 hover:text-gray-700 hover:bg-gray-200"
                    :icon="'bi bi-box-arrow-in-right'"
                >
                    登入
                </x-sidebar-link>

            {{-- 電腦版-已登入 --}}
            @else
                {{-- 電腦版-通知 --}}
                <x-sidebar-link
                    href="{{ route('notifications.index') }}"
                    class="{{
                        (auth()->user()->notification_count > 0) ?
                        'text-red-400 hover:text-red-500 hover:bg-gray-200' :
                        'text-gray-400 hover:text-gray-700 hover:bg-gray-200'
                    }}"
                    :icon="'bi bi-bell-fill'"
                >
                    通知
                </x-sidebar-link>

                {{-- 電腦版-會員選單 --}}
                <div
                    x-data="{ profileIsOpen: false }"
                    class="relative flex justify-center items-center pt-1"
                >
                    {{-- 電腦版-會員大頭貼 --}}
                    <div>
                        <button
                            x-on:click="profileIsOpen = ! profileIsOpen"
                            x-on:keydown.escape.window="profileIsOpen = false"
                            type="button"
                            class=" bg-gray-800 flex text-sm rounded-full
                            focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-blue-400 focus:ring-white"
                            id="user-menu-button" aria-expanded="false" aria-haspopup="true"
                        >
                            <span class="sr-only">Open user menu</span>
                            <img class="h-10 w-10 rounded-full" src="{{ auth()->user()->gravatar() }}" alt="">
                        </button>
                    </div>

                    {{-- 電腦版-會員下拉式選單 --}}
                    <div
                        x-cloak
                        x-on:click.outside="profileIsOpen = false"
                        x-show="profileIsOpen"
                        x-transition.origin.bottom.left
                        class="absolute left-16 bottom-0 p-2 mt-2 w-48 rounded-md shadow-lg bg-white text-gray-700 ring-1 ring-black ring-opacity-20
                        dark:bg-gray-500 dark:text-white"
                        role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1"
                    >
                        <a href="{{ route('users.show', ['user' => auth()->id()]) }}"
                        role="menuitem" tabindex="-1"
                        class="block px-4 py-2 rounded-md hover:bg-gray-200 dark:hover:bg-gray-400">
                            <i class="bi bi-person-fill"></i><span class="ml-2">個人頁面</span>
                        </a>

                        <a href="{{ route('users.edit', ['user' => auth()->id()]) }}"
                        role="menuitem" tabindex="-1"
                        class="block px-4 py-2 rounded-md hover:bg-gray-200 dark:hover:bg-gray-400">
                            <i class="bi bi-person-circle"></i><span class="ml-2">會員中心</span>
                        </a>

                        <button
                            type="submit" form="logout"
                            role="menuitem" tabindex="-1"
                            class="flex items-start w-full px-4 py-2 rounded-md hover:bg-gray-200 dark:hover:bg-gray-400"
                        >
                            <i class="bi bi-box-arrow-left"></i><span class="ml-2">登出</span>
                        </button>
                    </div>
                </div>
            @endguest
        </div>
    </div>
</nav>


