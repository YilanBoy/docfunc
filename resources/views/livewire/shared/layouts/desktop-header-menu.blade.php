<div
  class="relative hidden h-20 w-full items-center justify-center bg-gray-50 shadow-lg transition-all duration-300 dark:bg-gray-800 dark:shadow-none lg:flex"
  x-data
>
  {{-- 電腦版-Logo --}}
  <a
    class="absolute inset-y-1/2 left-4 flex items-center"
    href="{{ route('root') }}"
    wire:navigate
  >
    <img
      class="h-10 w-10"
      src="{{ asset('images/icon/icon.svg') }}"
      alt="logo"
    >
    <span class="ml-3 font-mono text-2xl font-bold dark:text-gray-50">{{ config('app.name') }}</span>
  </a>

  <ul class="flex space-x-6">

    <x-floating-underline-button
      :link="route('posts.index')"
      :selected="request()->url() === route('posts.index')"
      :icon-class="'bi bi-house-fill'"
    >
      全部文章
    </x-floating-underline-button>

    @foreach ($categories as $category)
      <x-floating-underline-button
        :link="$category->link_with_name"
        :selected="request()->url() === $category->link_with_name"
        :icon-class="$category->icon"
      >
        {{ $category->name }}
      </x-floating-underline-button>
    @endforeach
  </ul>

  <div class="absolute inset-y-1/2 right-6 flex items-center space-x-5">

    {{-- 明亮 / 暗黑模式切換 --}}
    <button
      class="group flex h-10 w-10 items-center justify-center"
      type="button"
      x-data="{ html: document.documentElement }"
      x-on:click="
        if (html.classList.contains('dark')) {
          html.classList.remove('dark')
          localStorage.setItem('mode', 'light')
        } else {
          html.classList.add('dark')
          localStorage.setItem('mode', 'dark')
        }
      "
    >
      <span class="text-xl text-amber-400 transition duration-150 group-hover:text-amber-500 dark:hidden">
        <i class="bi bi-sun-fill"></i>
      </span>

      <span class="hidden text-xl text-[#f6f1d5] transition duration-150 group-hover:text-[#ddd8bf] dark:inline">
        <i class="bi bi-moon-stars-fill"></i>
      </span>
    </button>

    {{-- dektop search --}}
    <livewire:shared.search />

    @guest
      {{-- 電腦版-未登入 --}}
      @if ($showRegisterButton)
        <a
          class="flex h-10 items-center justify-center rounded-lg border border-blue-600 bg-transparent px-3 text-blue-600 transition duration-150 hover:border-transparent hover:bg-blue-600 hover:text-gray-50"
          href="{{ route('register') }}"
          wire:navigate
        >
          註冊
        </a>
      @endif

      @if (request()->url() !== route('login'))
        <a
          class="flex h-10 items-center justify-center rounded-lg border border-emerald-600 bg-transparent px-3 text-emerald-600 transition duration-150 hover:border-transparent hover:bg-emerald-600 hover:text-gray-50"
          href="{{ route('login') }}"
          wire:navigate
        >
          <i class="bi bi-box-arrow-in-right"></i><span class="ml-2">登入</span>
        </a>
      @endif
    @else
      {{-- 電腦版-已登入 --}}

      {{-- 電腦版-通知 --}}
      <span class="relative inline-flex rounded-md">
        <a
          class="flex h-10 w-10 items-center justify-center rounded-lg text-xl text-gray-400 transition duration-150 hover:bg-gray-200 hover:text-gray-700 dark:hover:bg-gray-600 dark:hover:text-gray-50"
          href="{{ route('notifications.index') }}"
          wire:navigate
        >
          <i class="bi bi-bell-fill"></i>
        </a>

        @if (auth()->user()->notification_count > 0)
          <span class="absolute right-2 top-2 -mr-1 -mt-1 flex h-3 w-3">
            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-red-400 opacity-75"></span>
            <span class="relative inline-flex h-3 w-3 rounded-full bg-red-500"></span>
          </span>
        @endif
      </span>

      {{-- 電腦版-會員選單 --}}
      <div
        class="relative flex items-center justify-center"
        x-data="{ profileIsOpen: false }"
      >
        {{-- 電腦版-會員大頭貼 --}}
        <div>
          <button
            class="flex rounded-full bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-blue-400"
            id="user-menu-button"
            type="button"
            aria-expanded="false"
            aria-haspopup="true"
            x-on:click="profileIsOpen = ! profileIsOpen"
            x-on:keydown.escape.window="profileIsOpen = false"
          >
            <span class="sr-only">Open user menu</span>
            <img
              class="h-12 w-12 rounded-full"
              src="{{ auth()->user()->gravatar_url }}"
              alt=""
            >
          </button>
        </div>

        {{-- 電腦版-會員下拉式選單 --}}
        <div
          class="absolute right-0 top-16 mt-2 w-48 rounded-md bg-gray-50 p-2 text-gray-700 shadow-lg ring-1 ring-black ring-opacity-20 dark:bg-gray-800 dark:text-gray-50 dark:shadow-none dark:ring-gray-600"
          role="menu"
          aria-orientation="vertical"
          aria-labelledby="user-menu-button"
          tabindex="-1"
          x-cloak
          x-on:click.outside="profileIsOpen = false"
          x-show="profileIsOpen"
          x-transition.origin.top.right
        >
          <a
            class="block rounded-md px-4 py-2 hover:bg-gray-200 dark:hover:bg-gray-700"
            href="{{ route('posts.create') }}"
            role="menuitem"
            tabindex="-1"
            wire:navigate
          >
            <i class="bi bi-pencil-fill"></i><span class="ml-2">新增文章</span>
          </a>

          <a
            class="block rounded-md px-4 py-2 hover:bg-gray-200 dark:hover:bg-gray-700"
            href="{{ route('users.show', ['user' => auth()->id()]) }}"
            role="menuitem"
            tabindex="-1"
            wire:navigate
          >
            <i class="bi bi-info-circle-fill"></i><span class="ml-2">個人資訊</span>
          </a>

          <a
            class="block rounded-md px-4 py-2 hover:bg-gray-200 dark:hover:bg-gray-700"
            href="{{ route('users.edit', ['user' => auth()->id()]) }}"
            role="menuitem"
            tabindex="-1"
            wire:navigate
          >
            <i class="bi bi-person-circle"></i><span class="ml-2">會員中心</span>
          </a>

          <button
            class="flex w-full items-start rounded-md px-4 py-2 hover:bg-gray-200 dark:hover:bg-gray-700"
            type="button"
            role="menuitem"
            tabindex="-1"
            wire:confirm="你確定要登出嗎？"
            wire:click="$parent.logout"
          >
            <i class="bi bi-box-arrow-left"></i><span class="ml-2">登出</span>
          </button>
        </div>
      </div>
    @endguest
  </div>
</div>
