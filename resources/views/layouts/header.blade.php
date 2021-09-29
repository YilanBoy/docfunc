{{-- Header --}}
<nav id="header" class="z-20">
    {{-- 手機版選單 --}}
    <div
        x-data="{ mobileMenuIsOpen: false }"
        class="shadow-md bg-gray-50 lg:hidden dark:bg-gray-700"
    >
        <div class="px-2 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="relative flex items-center justify-between h-18">
                <div
                    class="absolute inset-y-0 left-0 flex items-center"
                >
                    {{-- 手機版-開關選單按鈕 --}}
                    <button
                        x-on:click="mobileMenuIsOpen = ! mobileMenuIsOpen"
                        type="button"
                        class="inline-flex items-center justify-center p-2 text-gray-700 rounded-md"
                        aria-controls="mobile-menu"
                        aria-expanded="false"
                    >
                        <span class="sr-only">Open main menu</span>
                            {{-- 手機版-關閉選單的 icon --}}
                        <div
                            :class="mobileMenuIsOpen ? 'hidden' : 'block'"
                            class="text-3xl text-gray-400 hover:text-gray-700 dark:hover:text-gray-50"
                        >
                            <i class="bi bi-list"></i>
                        </div>
                        {{-- 手機版-開啟選單的 icon --}}
                        <div
                            x-cloak
                            :class="mobileMenuIsOpen ? 'block' : 'hidden'"
                            class="text-xl text-gray-400 hover:text-gray-700 dark:hover:text-gray-50"
                        >
                            <i class="bi bi-x-lg"></i>
                        </div>
                    </button>
                </div>

                <div class="flex items-center mx-auto">
                    <span
                        class="hidden font-mono text-xl font-bold md:block dark:text-gray-50"
                    >
                        {{ config('app.name') }}
                    </span>
                </div>

                <div class="absolute inset-y-0 right-0 flex items-center">
                    {{-- 明亮 / 暗黑模式切換 --}}
                    <button type="button" class="mr-3 text-gray-400 theme-switch hover:text-gray-700 dark:hover:text-gray-50">
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
                        class="px-4 py-2 mr-3 text-blue-700 bg-transparent border border-blue-500 rounded-md hover:bg-blue-500 hover:text-gray-50 hover:border-transparent">
                            <i class="bi bi-person-plus-fill"></i><span class="hidden ml-2 md:inline">註冊</span>
                        </a>

                        <a href="{{ route('login') }}"
                        class="text-gray-400 hover:text-gray-700 dark:hover:text-gray-50">
                            <i class="bi bi-box-arrow-in-right"></i><span class="hidden ml-2 md:inline">登入</span>
                        </a>

                    {{-- 手機版-已登入 --}}
                    @else
                        {{-- 手機版-通知 --}}
                        <a
                            href="{{ route('notifications.index') }}"
                            @class([
                                'p-1 mr-3 rounded-full',
                                'text-red-400 hover:text-red-700' => (auth()->user()->notification_count > 0),
                                'text-gray-400 hover:text-gray-700 dark:hover:text-gray-50' => (auth()->user()->notification_count === 0),
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
                                    class="flex text-sm bg-gray-800 rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-blue-400 focus:ring-white"
                                    id="user-menu-button" aria-expanded="false" aria-haspopup="true"
                                >
                                    <span class="sr-only">Open user menu</span>
                                    <img class="w-10 h-10 rounded-full " src="{{ auth()->user()->gravatar() }}" alt="">
                                </button>
                            </div>

                            {{-- 手機版-會員下拉式選單 --}}
                            <div
                                x-cloak
                                x-on:click.outside="profileIsOpen = false"
                                x-show="profileIsOpen"
                                x-transition.origin.top.right
                                class="absolute right-0 w-48 p-2 mt-2 text-gray-700 rounded-md shadow-lg bg-gray-50 ring-1 ring-black ring-opacity-20 dark:bg-gray-700 dark:text-gray-50 dark:ring-gray-500"
                                role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1"
                            >
                                <a href="{{ route('posts.create') }}"
                                role="menuitem" tabindex="-1"
                                class="block px-4 py-2 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600">
                                    <i class="bi bi-pencil-fill"></i><span class="ml-2">新增文章</span>
                                </a>

                                <a href="{{ route('users.show', ['user' => auth()->id()]) }}"
                                role="menuitem" tabindex="-1"
                                class="block px-4 py-2 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600">
                                    <i class="bi bi-person-fill"></i><span class="ml-2">個人頁面</span>
                                </a>

                                <a href="{{ route('users.edit', ['user' => auth()->id()]) }}"
                                role="menuitem" tabindex="-1"
                                class="block px-4 py-2 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600">
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
                                    class="flex items-start w-full px-4 py-2 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600"
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
        class="items-center justify-between hidden w-full h-16 px-4 py-2 transition-all duration-300 shadow-md lg:flex bg-gray-50 dark:bg-gray-700"
    >
        {{-- 電腦版-Logo --}}
        <a href="{{ route('root') }}" class="font-mono text-2xl font-bold dark:text-gray-50">
            {{ config('app.name') }}
        </a>

        <ul class="flex space-x-2">
            <li>
                <a
                    href="{{ route('posts.index') }}"
                    @class([
                        'flex items-center justify-center h-10 px-3 transition duration-150 rounded-lg',
                        'bg-gray-200 text-gray-700' => (request()->url() === route('posts.index')),
                        'text-gray-400 hover:text-gray-700 hover:bg-gray-200' => (request()->url() !== route('posts.index')),
                    ])
                >
                    <i class="bi bi-house-fill"></i><span class="ml-2">全部文章</span>
                </a>
            </li>

            @foreach ($categories as $category)
                <li>
                    <a
                        href="{{ $category->link_with_name }}"
                        @class([
                            'flex items-center justify-center h-10 px-3 transition duration-150 rounded-lg',
                            'bg-gray-200 text-gray-700' => (request()->url() === $category->link_with_name),
                            'text-gray-400 hover:text-gray-700 hover:bg-gray-200' => (request()->url() !== $category->link_with_name),
                        ])
                    >
                        <i class="{{ $category->icon }}"></i><span class="ml-2">{{ $category->name }}</span>
                    </a>
                </li>
            @endforeach
        </ul>

        <div class="flex space-x-2">

            {{-- 電腦版-搜尋 --}}
            @livewire('search')

            {{-- 明亮 / 暗黑模式切換 --}}
            <button
                type="button"
                class="flex items-center justify-center w-10 h-10 text-xl text-gray-400 transition duration-150 rounded-lg theme-switch hover:text-gray-700 hover:bg-gray-200"
            >
                <span class="dark:hidden">
                    <i class="bi bi-sun-fill"></i>
                </span>

                <span class="hidden dark:inline">
                    <i class="bi bi-moon-stars-fill"></i>
                </span>
            </button>


            {{-- 電腦版-未登入 --}}
            @guest
                <a href="{{ route('register') }}"
                class="flex items-center justify-center h-10 px-3 mr-3 text-blue-400 transition duration-150 bg-transparent border border-blue-400 rounded-lg hover:bg-blue-400 hover:text-gray-50 hover:border-transparent">
                    註冊
                </a>

                <a href="{{ route('login') }}"
                class="flex items-center justify-center h-10 px-3 text-gray-400 transition duration-150 rounded-lg hover:text-gray-700 hover:bg-gray-200">
                    <i class="bi bi-box-arrow-in-right"></i><span class="ml-2">登入</span>
                </a>

            {{-- 電腦版-已登入 --}}
            @else
                {{-- 電腦版-通知 --}}
                <a
                    href="{{ route('notifications.index') }}"
                    @class([
                        'flex items-center justify-center w-10 h-10 text-xl transition duration-150 rounded-lg',
                        'text-red-400 hover:text-red-500 hover:bg-gray-200' => (auth()->user()->notification_count > 0),
                        'text-gray-400 hover:text-gray-700 hover:bg-gray-200' => (auth()->user()->notification_count === 0),
                    ])
                >
                    <i class="bi bi-bell-fill"></i>
                </a>

                {{-- 電腦版-會員選單 --}}
                <div
                    x-data="{ profileIsOpen: false }"
                    class="relative flex items-center justify-center"
                >
                    {{-- 電腦版-會員大頭貼 --}}
                    <div>
                        <button
                            x-on:click="profileIsOpen = ! profileIsOpen"
                            x-on:keydown.escape.window="profileIsOpen = false"
                            type="button"
                            class="flex text-sm bg-gray-800 rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-blue-400 focus:ring-white"
                            id="user-menu-button" aria-expanded="false" aria-haspopup="true"
                        >
                            <span class="sr-only">Open user menu</span>
                            <img class="w-10 h-10 rounded-full" src="{{ auth()->user()->gravatar() }}" alt="">
                        </button>
                    </div>

                    {{-- 電腦版-會員下拉式選單 --}}
                    <div
                        x-cloak
                        x-on:click.outside="profileIsOpen = false"
                        x-show="profileIsOpen"
                        x-transition.origin.top.right
                        class="absolute right-0 w-48 p-2 mt-2 text-gray-700 rounded-md shadow-lg top-14 bg-gray-50 ring-1 ring-black ring-opacity-20 dark:bg-gray-700 dark:text-gray-50 dark:ring-gray-500"
                        role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1"
                    >
                        <a href="{{ route('posts.create') }}"
                        role="menuitem" tabindex="-1"
                        class="block px-4 py-2 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600">
                            <i class="bi bi-pencil-fill"></i><span class="ml-2">新增文章</span>
                        </a>

                        <a href="{{ route('users.show', ['user' => auth()->id()]) }}"
                        role="menuitem" tabindex="-1"
                        class="block px-4 py-2 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600">
                            <i class="bi bi-person-fill"></i><span class="ml-2">個人頁面</span>
                        </a>

                        <a href="{{ route('users.edit', ['user' => auth()->id()]) }}"
                        role="menuitem" tabindex="-1"
                        class="block px-4 py-2 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600">
                            <i class="bi bi-person-circle"></i><span class="ml-2">會員中心</span>
                        </a>

                        <button
                            type="submit" form="logout"
                            role="menuitem" tabindex="-1"
                            class="flex items-start w-full px-4 py-2 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600"
                        >
                            <i class="bi bi-box-arrow-left"></i><span class="ml-2">登出</span>
                        </button>
                    </div>
                </div>
            @endguest
        </div>
    </div>
</nav>


