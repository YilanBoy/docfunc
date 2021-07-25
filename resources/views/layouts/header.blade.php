{{-- Header --}}
<nav>
    {{-- 手機版選單 --}}
    <div
        x-data="{ mobileMenuIsOpen : false }"
        id="header"
        class="relative bg-white border-blue-400 border-t-4 shadow-md
        dark:bg-gray-800 lg:hidden"
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
                    <img class="block h-8 w-auto" src="{{ asset('images/icon/icon.png') }}" alt="{{ config('app.name') }}">
                    <span
                        class="hidden md:block font-bold font-mono text-xl ml-2
                        dark:text-white"
                    >
                        {{ config('app.name') }}
                    </span>
                </div>

                <div class="absolute inset-y-0 right-0 flex items-center">

                    {{-- 手機版-未登入 --}}
                    @guest
                        <a href="{{ route('login') }}"
                        class="mr-3 text-gray-400 hover:text-gray-700 dark:hover:text-white">
                            <i class="bi bi-box-arrow-in-right"></i><span class="ml-2">登入</span>
                        </a>

                        <a href="{{ route('register') }}"
                        class="bg-transparent hover:bg-blue-500 text-blue-700 hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded-md">
                            註冊
                        </a>

                    {{-- 手機版-已登入 --}}
                    @else
                        {{-- 手機版-新增文章 --}}
                        @if (request()->url() !== route('posts.create'))
                            <a href="{{ route('posts.create') }}" class="p-1 mr-3 rounded-full text-gray-400 hover:text-gray-700
                            dark:hover:text-white">
                                <span class="sr-only">Create Post</span>
                                <i class="bi bi-plus-lg"></i>
                            </a>
                        @endif

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
                            <i class="text-lg bi bi-bell-fill"></i>
                        </a>

                        {{-- 手機版-會員選單 --}}
                        <div
                            x-data="{ profileIsOpen : false }"
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
                                class="absolute right-0 z-20 p-2 mt-2 w-48 rounded-md shadow-lg bg-white text-gray-700 ring-1 ring-black ring-opacity-20
                                dark:bg-gray-500 dark:text-white"
                                role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1"
                            >
                                <a href="{{ route('users.show', ['user' => auth()->id()]) }}"
                                role="menuitem" tabindex="-1"
                                class="block px-4 py-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-400">
                                    <i class="bi bi-person-fill"></i><span class="ml-2">個人頁面</span>
                                </a>

                                <a href="{{ route('users.edit', ['user' => auth()->id()]) }}"
                                role="menuitem" tabindex="-1"
                                class="block px-4 py-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-400">
                                    <i class="bi bi-person-circle"></i><span class="ml-2">會員中心</span>
                                </a>

                                <form id="logout" action="{{ route('logout') }}" method="POST" onSubmit="return confirm('您確定要登出？');"
                                class="hidden"
                                >
                                    @csrf
                                </form>

                                <button
                                    type="submit" form="logout"
                                    role="menuitem" tabindex="-1" id="user-menu-item-2"
                                    class="flex items-start w-full px-4 py-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-400"
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
                        'bg-gray-100 text-gray-700' => (request()->url() === route('posts.index')),
                        'text-gray-400 hover:text-gray-700 hover:bg-gray-100' => (request()->url() !== route('posts.index')),
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
                            'bg-gray-100 text-gray-700' => (request()->url() === $category->link_with_name),
                            'text-gray-400 hover:text-gray-700 hover:bg-gray-100' => (request()->url() !== $category->link_with_name),
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
        class="hidden lg:flex flex-col justify-between fixed top-0 left-0 z-20 w-20 h-screen
        bg-white border-blue-400 border-l-4 shadow-md transition-all duration-300 px-2 pt-6 pb-2
        dark:bg-gray-900"
    >
        {{-- 電腦版-側邊欄 --}}
        <div
            class="relative w-full flex justify-center items-center"
        >
            <img class="peer h-10 w-10" src="{{ asset('images/icon/icon.png') }}" alt="{{ config('app.name') }}">

            <span
                class="absolute top-4 left-20 flex justify-center items-center w-max opacity-0 transition-all duration-300
                text-gray-900 text-2xl font-bold font-mono bg-white rounded-md ring-1 ring-black ring-opacity-20 px-6 py-2 pointer-events-none
                peer-hover:opacity-100 peer-hover:top-0
                dark:bg-gray-500 dark:text-white"
            >
                {{ config('app.name') }}
            </span>
        </div>

        <ul class="mt-6 space-y-2">
            {{-- 電腦版-搜尋 --}}
            <li
                x-data="{ showSearchBox : false }"
                class="relative"
            >
                <button
                    x-on:click="showSearchBox = !showSearchBox"
                    type="button"
                    class="w-full flex justify-center items-center text-2xl rounded-md text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition duration-150 p-2"
                >
                    <i class="bi bi-search"></i>
                </button>

                <div
                    x-cloak
                    x-show="showSearchBox"
                    x-on:click.outside="showSearchBox = false"
                    x-on:keydown.escape.window="showSearchBox = false"
                    x-transition.origin.left
                    class="absolute top-1 left-20 w-60"
                >
                    @livewire('search')
                </div>
            </li>

            <li class="relative">
                <a
                    href="{{ route('posts.index') }}"
                    @class([
                        'peer flex justify-center items-center text-2xl rounded-md transition duration-150 p-2',
                        'bg-gray-100 text-gray-700' => (request()->url() === route('posts.index')),
                        'text-gray-400 hover:text-gray-700 hover:bg-gray-100' => (request()->url() !== route('posts.index')),
                    ])
                >
                    <i class="bi bi-house-fill"></i>
                </a>

                <span
                    class="absolute left-20 -top-3 flex justify-center items-center w-max opacity-0 transition-all duration-300
                    text-gray-900 bg-white rounded-md ring-1 ring-black ring-opacity-20 px-4 py-2 pointer-events-none
                    peer-hover:opacity-100 peer-hover:top-1
                    dark:bg-gray-500 dark:text-white"
                >
                    全部文章
                </span>
            </li>

            @foreach ($categories as $category)
                <li class="relative">
                    <a
                        href="{{ $category->link_with_name }}"
                        @class([
                            'peer flex justify-center items-center text-2xl rounded-md transition duration-150 p-2',
                            'bg-gray-100 text-gray-700' => (request()->url() === $category->link_with_name),
                            'text-gray-400 hover:text-gray-700 hover:bg-gray-100' => (request()->url() !== $category->link_with_name),
                        ])
                    >
                        <i class="{{ $category->icon }}"></i>
                    </a>

                    <span
                        class="absolute left-20 -top-3 flex justify-center items-center w-max opacity-0 transition-all duration-300
                        text-gray-900 bg-white rounded-md ring-1 ring-black ring-opacity-20 px-4 py-2 pointer-events-none
                        peer-hover:opacity-100 peer-hover:top-1
                        dark:bg-gray-500 dark:text-white"
                    >
                        {{ $category->name }}
                    </span>
                </li>
            @endforeach
        </ul>

        <div class="flex flex-col space-y-2">
            {{-- 電腦版-未登入 --}}
            @guest
                <div class="relative">
                    <a
                        href="{{ route('login') }}"
                        class="peer flex justify-center items-center text-2xl text-gray-400 hover:text-gray-700 hover:bg-gray-100
                        rounded-md transition duration-150 p-2"
                    >
                        <i class="bi bi-box-arrow-in-right"></i>
                    </a>

                    <span
                        class="absolute left-20 -top-3 flex justify-center items-center w-max opacity-0 transition-all duration-300
                        text-gray-900 bg-white rounded-md ring-1 ring-black ring-opacity-20 px-4 py-2 pointer-events-none
                        peer-hover:opacity-100 peer-hover:top-1
                        dark:bg-gray-500 dark:text-white"
                    >
                        登入
                    </span>
                </div>

                <div class="relative">
                    <a
                        href="{{ route('register') }}"
                        class="peer flex justify-center items-center text-2xl text-gray-400 hover:text-gray-700 hover:bg-gray-100
                        rounded-md transition duration-150 p-2"
                    >
                        <i class="bi bi-person-plus-fill"></i>
                    </a>

                    <span
                        class="absolute left-20 -top-3 flex justify-center items-center w-max opacity-0 transition-all duration-300
                        text-gray-900 bg-white rounded-md ring-1 ring-black ring-opacity-20 px-4 py-2 pointer-events-none
                        peer-hover:opacity-100 peer-hover:top-1
                        dark:bg-gray-500 dark:text-white"
                    >
                        註冊
                    </span>
                </div>

            {{-- 電腦版-已登入 --}}
            @else
                {{-- 電腦版-新增文章 --}}
                @if (request()->url() !== route('posts.create'))
                    <div class="relative">
                        <a
                            href="{{ route('posts.create') }}"
                            class="peer flex justify-center items-center text-2xl text-gray-400 hover:text-gray-700 hover:bg-gray-100
                            rounded-md transition duration-150 p-2"
                        >
                            <i class="bi bi-plus-lg"></i>
                        </a>

                        <span
                            class="absolute left-20 -top-3 flex justify-center items-center w-max opacity-0 transition-all duration-300
                            text-gray-900 bg-white rounded-md ring-1 ring-black ring-opacity-20 px-4 py-2 pointer-events-none
                            peer-hover:opacity-100 peer-hover:top-1
                            dark:bg-gray-500 dark:text-white"
                        >
                            新增文章
                        </span>
                    </div>
                @endif

                {{-- 電腦版-通知 --}}
                <div class="relative">
                    <a
                        href="{{ route('notifications.index') }}"
                        @class([
                            'peer flex justify-center items-center text-2xl rounded-md transition duration-150 p-2',
                            'text-red-400 hover:text-red-500 hover:bg-gray-100' => (auth()->user()->notification_count > 0),
                            'text-gray-400 hover:text-gray-700 hover:bg-gray-100' => (auth()->user()->notification_count === 0),
                        ])
                    >
                        <i class="text-lg bi bi-bell-fill"></i>
                    </a>

                    <span
                        class="absolute left-20 -top-3 flex justify-center items-center w-max opacity-0 transition-all duration-300
                        text-gray-900 bg-white rounded-md ring-1 ring-black ring-opacity-20 px-4 py-2 pointer-events-none
                        peer-hover:opacity-100 peer-hover:top-1
                        dark:bg-gray-500 dark:text-white"
                    >
                        通知
                    </span>
                </div>

                {{-- 電腦版-會員選單 --}}
                <div
                    x-data="{ profileIsOpen : false }"
                    class="relative flex justify-center items-center"
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
                            <img class=" h-10 w-10 rounded-full" src="{{ auth()->user()->gravatar() }}" alt="">
                        </button>
                    </div>

                    {{-- 電腦版-會員下拉式選單 --}}
                    <div
                        x-cloak
                        x-on:click.outside="profileIsOpen = false"
                        x-show="profileIsOpen"
                        x-transition.origin.bottom.left
                        class="absolute left-20 bottom-0 z-20 p-2 mt-2 w-48 rounded-md shadow-lg bg-white text-gray-700 ring-1 ring-black ring-opacity-20
                        dark:bg-gray-500 dark:text-white"
                        role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1"
                    >
                        <a href="{{ route('users.show', ['user' => auth()->id()]) }}"
                        role="menuitem" tabindex="-1"
                        class="block px-4 py-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-400">
                            <i class="bi bi-person-fill"></i><span class="ml-2">個人頁面</span>
                        </a>

                        <a href="{{ route('users.edit', ['user' => auth()->id()]) }}"
                        role="menuitem" tabindex="-1"
                        class="block px-4 py-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-400">
                            <i class="bi bi-person-circle"></i><span class="ml-2">會員中心</span>
                        </a>

                        <form id="logout" action="{{ route('logout') }}" method="POST" onSubmit="return confirm('您確定要登出？');"
                        class="hidden"
                        >
                            @csrf
                        </form>

                        <button
                            type="submit" form="logout"
                            role="menuitem" tabindex="-1" id="user-menu-item-2"
                            class="flex items-start w-full px-4 py-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-400"
                        >
                            <i class="bi bi-box-arrow-left"></i><span class="ml-2">登出</span>
                        </button>
                    </div>
                </div>
            @endguest

            {{-- 明亮 / 暗黑模式切換 --}}
            <div class="flex justify-center items-center h-10">
                <label for="theme-switch"
                class="w-9 h-6 flex items-center bg-gray-300 rounded-full p-1 cursor-pointer duration-300 ease-in-out dark:bg-gray-600">
                    <div class="bg-white w-4 h-4 rounded-full shadow-md transform duration-300 ease-in-out dark:translate-x-3"></div>
                </label>

                <input id="theme-switch" type="checkbox" class="hidden">
            </div>
        </div>


    </div>
</nav>


