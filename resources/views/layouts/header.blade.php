{{-- Header --}}
<nav
    x-data="{ mobileMenuIsOpen : false }"
    id="header"
    class="bg-white border-blue-400 border-t-4 shadow-md
    dark:bg-gray-800"
>
    <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
        <div class="relative flex items-center justify-between h-18">
            <div
                class="absolute inset-y-0 left-0 flex items-center sm:hidden"
            >
                {{-- 手機版選單按鈕 --}}
                <button
                    x-on:click="mobileMenuIsOpen = ! mobileMenuIsOpen"
                    type="button"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-700"
                    aria-controls="mobile-menu"
                    aria-expanded="false"
                >
                    <span class="sr-only">Open main menu</span>
                        {{-- 手機版關閉選單的 icon --}}
                    <div
                        :class="mobileMenuIsOpen ? 'hidden' : 'block'"
                        class="text-3xl text-gray-400 hover:text-gray-700"
                    >
                        <i class="bi bi-list"></i>
                    </div>
                    {{-- 手機版開啟選單的 icon --}}
                    <div
                        x-cloak
                        :class="mobileMenuIsOpen ? 'block' : 'hidden'"
                        class="text-xl text-gray-400 hover:text-gray-700"
                    >
                        <i class="bi bi-x-lg"></i>
                    </div>
                </button>
            </div>
            {{-- 電腦版選單 --}}
            <div class="flex-1 flex items-center justify-center sm:items-stretch sm:justify-start">
                <div class="flex-shrink-0 flex items-center">
                    <img class="block lg:hidden h-8 w-auto" src="{{ asset('images/icon/icon.png') }}" alt="{{ config('app.name') }}">
                    <span class="flex items-center justify-center">
                        <img class="hidden lg:block h-8 w-auto" src="{{ asset('images/icon/icon.png') }}" alt="{{ config('app.name') }}">
                        <span
                            class="hidden font-bold font-mono text-xl lg:block ml-2
                            dark:text-white"
                        >
                            {{ config('app.name') }}
                        </span>
                    </span>
                </div>
                <div class="hidden sm:block sm:ml-6">
                    <div class="flex space-x-2">
                        <!-- Current: "bg-gray-900 text-white", Default: "text-gray-300 hover:bg-gray-700 hover:text-white" -->
                        <a
                            href="{{ route('posts.index') }}"
                            class="
                            {{ (request()->url() === route('posts.index')) ? 'text-gray-700 dark:text-white' : 'text-gray-400 hover:text-gray-700 dark:hover:text-white' }}
                            text-lg p-2 font-medium"
                            @if(request()->url() === route('posts.index')) aria-current="page" @endif
                        >
                            <i class="bi bi-house-fill"></i><span class="ml-2">全部文章</span>
                        </a>
                        @foreach ($categories as $category)
                            <a
                                href="{{ $category->link_with_name }}"
                                class="
                                {{ (request()->url() === $category->link_with_name) ? 'text-gray-700 dark:text-white' : 'text-gray-400 hover:text-gray-700 dark:hover:text-white' }}
                                text-lg p-2 font-medium"
                                @if(request()->url() === $category->link_with_name) aria-current="page" @endif
                            >
                                <i class="{{ $category->icon }}"></i><span class="ml-2">{{ $category->name }}</span>
                            </a>
                        @endforeach

                        {{-- 搜尋 --}}
                        <div class="hidden relative xl:inline-block">
                            <input
                                type="search"
                                id="aa-search-input"
                                name="search"
                                autocomplete="off"
                                placeholder="Search"
                                class="outline-none w-3/4 rounded-xl bg-gray-100 placeholder-gray-400 border border-gray-400
                                focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 px-4 py-2 pl-8
                                dark:bg-gray-500 dark:placeholder-white dark:text-white"
                            />

                            <div class="absolute top-0 flex items-center h-full ml-2 text-gray-400
                            dark:text-white">
                                <i class="bi bi-search"></i>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="absolute inset-y-0 right-0 flex items-center pr-2 sm:static sm:inset-auto sm:ml-6 sm:pr-0">

                {{-- 未登入 --}}
                @guest

                    <a href="{{ route('login') }}"
                    class="mr-3 text-gray-400 hover:text-gray-700 dark:hover:text-white">
                        <i class="bi bi-box-arrow-in-right"></i><span class="ml-2">登入</span>
                    </a>

                    <a href="{{ route('register') }}"
                    class="bg-transparent hover:bg-blue-500 text-blue-700 hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded-md">
                        註冊
                    </a>

                {{-- 已登入 --}}
                @else
                    {{-- 新增文章 --}}
                    @if (request()->url() !== route('posts.create'))
                        <a href="{{ route('posts.create') }}" class="p-1 mr-3 rounded-full text-gray-400 hover:text-gray-700
                        dark:hover:text-white">
                            <span class="sr-only">Create Post</span>
                            <i class="bi bi-plus-lg"></i>
                        </a>
                    @endif

                    {{-- 通知 --}}
                    <a href="{{ route('notifications.index') }}"
                    class="p-1 mr-3 rounded-full
                    {{ (auth()->user()->notification_count > 0) ? 'text-red-400 hover:text-red-700' : 'text-gray-400 hover:text-gray-700 dark:hover:text-white' }}">
                        <span class="sr-only">View notifications</span>
                        <i class="text-lg bi bi-bell-fill"></i>
                    </a>

                    {{-- 使用者選單 --}}
                    <div
                        x-data="{ profileIsOpen : false }"
                        class="relative"
                    >
                        {{-- 使用者大頭貼 --}}
                        <div>
                            <button
                                x-on:click="profileIsOpen = ! profileIsOpen"
                                x-on:click.away="profileIsOpen = false"
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

                        {{-- 下拉式選單 --}}
                        <div
                            x-cloak
                            x-show.transition.duration.100ms.top.left="profileIsOpen"
                            class="origin-top-right absolute right-0 z-20 p-2 mt-2 w-48 rounded-md shadow-lg bg-white text-gray-700 ring-1 ring-black ring-opacity-20
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
                                <i class="bi bi-person-lines-fill"></i><span class="ml-2">編輯資料</span>
                            </a>

                            <form id="logout" action="{{ route('logout') }}" method="POST"
                            class="hidden"
                            >
                                @csrf
                            </form>

                            <button
                                x-on:click="return confirm('您確定要登出？');"
                                type="submit" form="logout"
                                role="menuitem" tabindex="-1" id="user-menu-item-2"
                                class="flex items-start w-full px-4 py-2 rounded-md hover:bg-gray-200 dark:hover:bg-gray-500"
                            >
                                <i class="bi bi-box-arrow-left"></i><span class="ml-2">登出</span>
                            </button>
                        </div>
                    </div>
                @endguest
            </div>
        </div>
    </div>

    {{-- 手機版選單 --}}
    <div
        x-cloak
        x-show.transition.duration.100ms.top.bottom="mobileMenuIsOpen"
        class="sm:hidden"
    >
        <div class="px-2 pt-2 pb-3 space-y-1">
            <a
                href="{{ route('posts.index') }}"
                class="
                {{ (request()->url() === route('posts.index')) ? 'bg-gray-200 text-gray-700' : 'text-gray-400 hover:text-gray-700 hover:bg-gray-200' }}
                block px-3 py-2 rounded-md font-medium"
                @if(request()->url() === route('posts.index')) aria-current="page" @endif
            >
                <i class="bi bi-house-fill"></i><span class="ml-2">全部文章</span>
            </a>
            @foreach ($categories as $category)
                <a
                    href="{{ $category->link_with_name }}"
                    class="
                    {{ (request()->url() === $category->link_with_name) ? 'bg-gray-200 text-gray-700' : 'text-gray-400 hover:text-gray-700 hover:bg-gray-200' }}
                    block px-3 py-2 rounded-md font-medium"
                    @if(request()->url() === $category->link_with_name) aria-current="page" @endif
                >
                    <i class="{{ $category->icon }}"></i><span class="ml-2">{{ $category->name }}</span>
                </a>
            @endforeach
        </div>
    </div>
</nav>
