<div
  x-data
  class="relative items-center justify-center hidden w-full h-16 transition-all duration-300 shadow-lg lg:flex bg-gray-50 dark:bg-gray-700"
>
  {{-- logout form --}}
  <form
    x-ref="logout"
    action="{{ route('logout') }}"
    method="POST"
    class="hidden"
  >
    @csrf
  </form>

  {{-- 電腦版-Logo --}}
  <a
    href="{{ route('root') }}"
    class="absolute flex items-center top-3 left-4"
  >
    <img src="{{ asset('images/icon/icon.svg') }}" alt="logo" class="w-10 h-10">
    <span class="ml-3 font-mono text-2xl font-bold dark:text-gray-50">{{ config('app.name') }}</span>
  </a>

  <ul class="flex space-x-6">
    <li class="relative">
      <a
        href="{{ route('posts.index') }}"
        @class([
          'peer flex items-center justify-center h-10 transition duration-150',
          'text-gray-400 hover:text-green-400 dark:hover:text-indigo-400' => request()->url() !== route('posts.index'),
          'text-green-400 dark:text-indigo-400' => request()->url() === route('posts.index'),
        ])
      >
        <i class="bi bi-house-fill"></i><span class="ml-2">全部文章</span>
      </a>

      <span @class([
          'absolute left-0 w-full h-1 transition-all duration-300 bg-green-400 dark:bg-indigo-400 rounded-full pointer-events-none',
          'opacity-0 -bottom-5 peer-hover:opacity-100 peer-hover:-bottom-3' => request()->url() !== route('posts.index'),
          'opacity-100 -bottom-3' => request()->url() === route('posts.index'),
        ])></span>
    </li>

    @foreach ($categories as $category)
      <li class="relative">
        <a
          href="{{ $category->link_with_name }}"
          @class([
            'peer flex items-center justify-center h-10 transition duration-150',
            'text-gray-400 hover:text-green-400 dark:hover:text-indigo-400' => request()->url() !== $category->link_with_name,
            'text-green-400 dark:text-indigo-400' => request()->url() === $category->link_with_name,
          ])
        >
          <i class="{{ $category->icon }}"></i><span class="ml-2">{{ $category->name }}</span>
        </a>

        <span @class([
            'absolute left-0 w-full h-1 transition-all duration-300 bg-green-400 dark:bg-indigo-400 rounded-full pointer-events-none',
            'opacity-0 -bottom-5 peer-hover:opacity-100 peer-hover:-bottom-3' => request()->url() !== $category->link_with_name,
            'opacity-100 -bottom-3' => request()->url() === $category->link_with_name,
          ])></span>
      </li>
    @endforeach
  </ul>

  <div class="absolute flex space-x-3 top-3 right-4">

    {{-- 明亮 / 暗黑模式切換 --}}
    <button
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
      type="button"
      class="flex items-center justify-center w-10 h-10 group"
    >
        <span class="transition duration-150 text-amber-400 dark:hidden group-hover:text-amber-500">
          <i class="bi bi-sun-fill"></i>
        </span>

      <span class="hidden transition duration-150 text-blue-500 dark:inline group-hover:text-blue-400">
          <i class="bi bi-moon-stars-fill"></i>
        </span>
    </button>

    {{-- dektop search --}}
    <livewire:components.search/>

    @guest
      {{-- 電腦版-未登入 --}}
      @if ($showRegisterButton)
        <a
          href="{{ route('register') }}"
          class="flex items-center justify-center h-10 px-3 text-blue-400 transition duration-150 bg-transparent border border-blue-400 rounded-lg hover:bg-blue-400 hover:text-gray-50 hover:border-transparent"
        >
          註冊
        </a>
      @endif

      @if (request()->url() !== route('login'))
        <a
          href="{{ route('login') }}"
          class="flex items-center justify-center h-10 px-3 transition duration-150 bg-transparent border rounded-lg text-emerald-400 border-emerald-400 hover:bg-emerald-400 hover:text-gray-50 hover:border-transparent"
        >
          <i class="bi bi-box-arrow-in-right"></i><span class="ml-2">登入</span>
        </a>
      @endif
    @else
      {{-- 電腦版-已登入 --}}

      {{-- 電腦版-通知 --}}
      <span class="relative inline-flex rounded-md">
        <a
          href="{{ route('notifications.index') }}"
          class="flex items-center justify-center w-10 h-10 text-gray-400 transition duration-150 rounded-lg hover:text-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 dark:hover:text-gray-50"
        >
          <i class="bi bi-bell-fill"></i>
        </a>

        @if (auth()->user()->notification_count > 0)
          <span class="absolute flex w-3 h-3 -mt-1 -mr-1 top-2 right-2">
            <span class="absolute inline-flex w-full h-full bg-red-400 rounded-full opacity-75 animate-ping"></span>
            <span class="relative inline-flex w-3 h-3 bg-red-500 rounded-full"></span>
          </span>
        @endif
      </span>

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
            id="user-menu-button"
            aria-expanded="false"
            aria-haspopup="true"
            class="flex text-sm bg-gray-800 rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-blue-400 focus:ring-white"
          >
            <span class="sr-only">Open user menu</span>
            <img
              class="w-10 h-10 rounded-full"
              src="{{ auth()->user()->gravatar_url }}"
              alt=""
            >
          </button>
        </div>

        {{-- 電腦版-會員下拉式選單 --}}
        <div
          x-cloak
          x-on:click.outside="profileIsOpen = false"
          x-show="profileIsOpen"
          x-transition.origin.top.right
          role="menu"
          aria-orientation="vertical"
          aria-labelledby="user-menu-button"
          tabindex="-1"
          class="absolute right-0 w-48 p-2 mt-2 text-gray-700 rounded-md shadow-lg top-14 bg-gray-50 ring-1 ring-black ring-opacity-20 dark:bg-gray-700 dark:text-gray-50 dark:ring-gray-500"
        >
          <a
            href="{{ route('posts.create') }}"
            role="menuitem"
            tabindex="-1"
            class="block px-4 py-2 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600"
          >
            <i class="bi bi-pencil-fill"></i><span class="ml-2">新增文章</span>
          </a>

          <a
            href="{{ route('users.index', ['user' => auth()->id()]) }}"
            role="menuitem"
            tabindex="-1"
            class="block px-4 py-2 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600"
          >
            <i class="bi bi-info-circle-fill"></i><span class="ml-2">個人資訊</span>
          </a>

          <a
            href="{{ route('users.edit', ['user' => auth()->id()]) }}"
            role="menuitem"
            tabindex="-1"
            class="block px-4 py-2 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600"
          >
            <i class="bi bi-person-circle"></i><span class="ml-2">會員中心</span>
          </a>

          <button
            x-on:click.prevent="
              if (confirm('您確定要登出？')) {
                $refs.logout.submit()
              }
            "
            type="button"
            role="menuitem"
            tabindex="-1"
            class="flex items-start w-full px-4 py-2 rounded-md hover:bg-gray-200 dark:hover:bg-gray-600"
          >
            <i class="bi bi-box-arrow-left"></i><span class="ml-2">登出</span>
          </button>
        </div>
      </div>
    @endguest
  </div>
</div>
