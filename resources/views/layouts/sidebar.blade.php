<div class="hidden sm:flex flex-col justify-between fixed top-0 left-0 z-20 w-20 h-screen bg-gray-900 transition-all duration-300 px-2 pt-6 pb-2">
    {{-- 側邊攔 --}}
    <div
        class="relative w-full flex justify-center items-center"
    >
        <img class="peer h-10 w-10" src="{{ asset('images/icon/icon.png') }}" alt="{{ config('app.name') }}">

        <span
            class="absolute top-0 left-20 flex justify-center items-center w-max opacity-0 peer-hover:opacity-100 transition duration-150
            text-gray-900 text-2xl font-bold font-mono bg-gray-200 rounded-md ring-1 ring-black ring-opacity-20 px-6 py-2 pointer-events-none"
        >
            {{ config('app.name') }}
        </span>
    </div>

    <ul class="mt-6 space-y-2">
        <li class="relative">
            <a
                href="{{ route('posts.index') }}"
                @class([
                    'peer flex justify-center items-center text-2xl rounded-md transition duration-150 p-2',
                    'text-gray-900 bg-gray-200' => (request()->url() === route('posts.index')),
                    'text-gray-200 hover:text-gray-900 hover:bg-gray-200' => (request()->url() !== route('posts.index')),
                ])
            >
                <i class="bi bi-house-fill"></i>
            </a>

            <span
                class="absolute top-1 left-20 flex justify-center items-center w-max opacity-0 peer-hover:opacity-100 transition duration-150
                text-gray-900 bg-gray-200 rounded-md ring-1 ring-black ring-opacity-20 px-4 py-2 pointer-events-none"
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
                        'text-gray-900 bg-gray-200' => (request()->url() === $category->link_with_name),
                        'text-gray-200 hover:text-gray-900 hover:bg-gray-200' => (request()->url() !== $category->link_with_name),
                    ])
                >
                    <i class="{{ $category->icon }}"></i>
                </a>

                <span
                    class="absolute top-1 left-20 flex justify-center items-center w-max opacity-0 peer-hover:opacity-100 transition duration-150
                    text-gray-900 bg-gray-200 rounded-md ring-1 ring-black ring-opacity-20 px-4 py-2 pointer-events-none"
                >
                    {{ $category->name }}
                </span>
            </li>
        @endforeach

        <li
            x-data="{ showSearchBox : false }"
            class="relative"
        >
            <button
                x-on:click="showSearchBox = !showSearchBox"
                type="button"
                class="w-full flex justify-center items-center text-2xl rounded-md text-gray-200 hover:text-gray-900 hover:bg-gray-200 transition duration-150 p-2"
            >
                <i class="bi bi-search"></i>
            </button>

            {{-- 搜尋 --}}
            <div
                x-cloak
                x-show="showSearchBox"
                x-on:click.outside="showSearchBox = false"
                x-on:keydown.escape.window="showSearchBox = false"
                x-transition.left.duration.300ms
                class="absolute top-1 left-20 w-60"
            >
                @livewire('search')
            </div>
        </li>
    </ul>

    <div class="flex flex-col space-y-2">
        {{-- 未登入 --}}
        @guest
            <div class="relative">
                <a
                    href="{{ route('login') }}"
                    class="peer flex justify-center items-center text-2xl text-gray-200 hover:text-gray-900 hover:bg-gray-200
                    rounded-md transition duration-150 p-2"
                >
                    <i class="bi bi-box-arrow-in-right"></i>
                </a>

                <span
                    class="absolute top-1 left-20 flex justify-center items-center w-max opacity-0 peer-hover:opacity-100 transition duration-150
                    text-gray-900 bg-gray-200 rounded-md ring-1 ring-black ring-opacity-20 px-4 py-2 pointer-events-none"
                >
                    登入
                </span>
            </div>

            <div class="relative">
                <a
                    href="{{ route('register') }}"
                    class="peer flex justify-center items-center text-2xl text-gray-200 hover:text-gray-900 hover:bg-gray-200
                    rounded-md transition duration-150 p-2"
                >
                    <i class="bi bi-person-plus-fill"></i>
                </a>

                <span
                    class="absolute top-1 left-20 flex justify-center items-center w-max opacity-0 peer-hover:opacity-100 transition duration-150
                    text-gray-900 bg-gray-200 rounded-md ring-1 ring-black ring-opacity-20 px-4 py-2 pointer-events-none"
                >
                    註冊
                </span>
            </div>

        {{-- 已登入 --}}
        @else
            {{-- 新增文章 --}}
            @if (request()->url() !== route('posts.create'))
                <div class="relative">
                    <a
                        href="{{ route('posts.create') }}"
                        class="peer flex justify-center items-center text-2xl text-gray-200 hover:text-gray-900 hover:bg-gray-200
                        rounded-md transition duration-150 p-2"
                    >
                        <i class="bi bi-plus-lg"></i>
                    </a>

                    <span
                        class="absolute top-1 left-20 flex justify-center items-center w-max opacity-0 peer-hover:opacity-100 transition duration-150
                        text-gray-900 bg-gray-200 rounded-md ring-1 ring-black ring-opacity-20 px-4 py-2 pointer-events-none"
                    >
                        新增文章
                    </span>
                </div>
            @endif

            {{-- 通知 --}}
            <div class="relative">
                <a
                    href="{{ route('notifications.index') }}"
                    @class([
                        'peer flex justify-center items-center text-2xl rounded-md transition duration-150 p-2',
                        'text-red-400 hover:text-red-500 hover:bg-gray-200' => (auth()->user()->notification_count > 0),
                        'text-gray-200 hover:text-gray-900 hover:bg-gray-200' => (auth()->user()->notification_count === 0),
                    ])
                >
                    <i class="text-lg bi bi-bell-fill"></i>
                </a>

                <span
                    class="absolute top-1 left-20 flex justify-center items-center w-max opacity-0 peer-hover:opacity-100 transition duration-150
                    text-gray-900 bg-gray-200 rounded-md ring-1 ring-black ring-opacity-20 px-4 py-2 pointer-events-none"
                >
                    通知
                </span>
            </div>

            {{-- 使用者選單 --}}
            <div
                x-data="{ profileIsOpen : false }"
                class="relative flex justify-center items-center"
            >
                {{-- 使用者大頭貼 --}}
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

                {{-- 下拉式選單 --}}
                <div
                    x-cloak
                    x-on:click.outside="profileIsOpen = false"
                    x-show="profileIsOpen"
                    x-transition.origin.bottom.left
                    class="absolute left-20 bottom-0 z-20 p-2 mt-2 w-48 rounded-md shadow-lg bg-white text-gray-700 ring-1 ring-black ring-opacity-20
                    dark:bg-gray-600 dark:text-white"
                    role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1"
                >
                    <a href="{{ route('users.show', ['user' => auth()->id()]) }}"
                    role="menuitem" tabindex="-1"
                    class="block px-4 py-2 rounded-md hover:bg-gray-200 dark:hover:bg-gray-500">
                        <i class="bi bi-person-fill"></i><span class="ml-2">個人頁面</span>
                    </a>

                    <a href="{{ route('users.edit', ['user' => auth()->id()]) }}"
                    role="menuitem" tabindex="-1"
                    class="block px-4 py-2 rounded-md hover:bg-gray-200 dark:hover:bg-gray-500">
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
                        class="flex items-start w-full px-4 py-2 rounded-md hover:bg-gray-200 dark:hover:bg-gray-500"
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
