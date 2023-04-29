<div
  x-data="{ menuIsOpen: false }"
  class="bg-gray-50 shadow-lg dark:bg-gray-700 lg:hidden"
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

  <div class="mx-auto max-w-7xl px-2 sm:px-6 lg:px-8">
    <div class="relative flex h-[4.5rem] items-center justify-between">
      <div class="absolute inset-y-0 left-0 flex items-center">
        {{-- 手機版-開關選單按鈕 --}}
        <button
          x-on:click="menuIsOpen = ! menuIsOpen"
          type="button"
          aria-controls="mobile-menu"
          aria-expanded="false"
          class="inline-flex items-center justify-center rounded-md p-2 text-gray-700"
        >
          <span class="sr-only">Open main menu</span>
          {{-- 手機版-關閉選單的 icon --}}
          <div
            :class="menuIsOpen ? 'hidden' : 'block'"
            class="text-3xl text-gray-400 hover:text-gray-700 dark:hover:text-gray-50"
          >
            <i class="bi bi-list"></i>
          </div>
          {{-- 手機版-開啟選單的 icon --}}
          <div
            x-cloak
            :class="menuIsOpen ? 'block' : 'hidden'"
            class="text-xl text-gray-400 hover:text-gray-700 dark:hover:text-gray-50"
          >
            <i class="bi bi-x-lg"></i>
          </div>
        </button>
      </div>

      <div class="mx-auto flex items-center">
        <img
          src="{{ asset('images/icon/icon.svg') }}"
          alt="logo"
          class="hidden h-10 w-10 md:inline-block"
        >
        <span class="ml-3 hidden font-mono text-xl font-bold dark:text-gray-50 md:block">
          {{ config('app.name') }}
        </span>
      </div>

      <div class="absolute inset-y-0 right-0 flex items-center space-x-6">
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
        >
          <span class="text-amber-400 hover:text-amber-500 dark:hidden">
            <i class="bi bi-sun-fill"></i>
          </span>

          <span class="hidden text-blue-500 hover:text-blue-400 dark:inline">
            <i class="bi bi-moon-stars-fill"></i>
          </span>
        </button>

        @guest
          {{-- 手機版-未登入 --}}
          @if ($showRegisterButton)
            <a
              href="{{ route('register') }}"
              class="rounded-md border border-blue-400 bg-transparent px-4 py-2 text-blue-400 hover:border-transparent hover:bg-blue-400 hover:text-gray-50"
            >
              註冊
            </a>
          @endif

          @if (request()->url() !== route('login'))
            <a
              href="{{ route('login') }}"
              class="rounded-md border border-emerald-400 bg-transparent px-4 py-2 text-emerald-400 hover:border-transparent hover:bg-emerald-400 hover:text-gray-50"
            >
              登入
            </a>
          @endif
        @else
          {{-- 手機版-已登入 --}}

          {{-- 手機版-通知 --}}
          <span class="relative inline-flex rounded-md">
            <a
              href="{{ route('notifications.index') }}"
              class="rounded-full text-gray-400 hover:text-gray-700 dark:hover:text-gray-50"
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

          {{-- 手機版-會員選單 --}}
          <div
            x-data="{ showDropdown: false }"
            class="relative"
          >
            {{-- 手機版-會員大頭貼 --}}
            <div>
              <button
                x-on:click="showDropdown = ! showDropdown"
                x-on:keydown.escape.window="showDropdown = false"
                type="button"
                id="user-menu-button"
                aria-expanded="false"
                aria-haspopup="true"
                class="flex rounded-full bg-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-blue-400"
              >
                <span class="sr-only">Open user menu</span>
                <img
                  class="h-10 w-10 rounded-full"
                  src="{{ auth()->user()->gravatar_url }}"
                  alt=""
                >
              </button>
            </div>

            {{-- 手機版-會員下拉式選單 --}}
            <div
              x-cloak
              x-on:click.outside="showDropdown = false"
              x-show="showDropdown"
              x-transition.origin.top.right
              role="menu"
              aria-orientation="vertical"
              aria-labelledby="user-menu-button"
              tabindex="-1"
              class="absolute right-0 mt-2 w-48 rounded-md bg-gray-50 p-2 text-gray-700 shadow-lg ring-1 ring-black ring-opacity-20 dark:bg-gray-700 dark:text-gray-50 dark:ring-gray-500"
            >
              <a
                href="{{ route('posts.create') }}"
                role="menuitem"
                tabindex="-1"
                class="block rounded-md px-4 py-2 hover:bg-gray-200 dark:hover:bg-gray-600"
              >
                <i class="bi bi-pencil-fill"></i><span class="ml-2">新增文章</span>
              </a>

              <a
                href="{{ route('users.index', ['user' => auth()->id()]) }}"
                role="menuitem"
                tabindex="-1"
                class="block rounded-md px-4 py-2 hover:bg-gray-200 dark:hover:bg-gray-600"
              >
                <i class="bi bi-info-circle-fill"></i><span class="ml-2">個人資訊</span>
              </a>

              <a
                href="{{ route('users.edit', ['user' => auth()->id()]) }}"
                role="menuitem"
                tabindex="-1"
                class="block rounded-md px-4 py-2 hover:bg-gray-200 dark:hover:bg-gray-600"
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
                class="flex w-full items-start rounded-md px-4 py-2 hover:bg-gray-200 dark:hover:bg-gray-600"
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
    x-show="menuIsOpen"
    x-collapse
    class="lg:hidden"
  >
    <div class="space-y-1 px-2 pb-3 pt-2">
      <a
        href="{{ route('posts.index') }}"
        @class([
            'block px-3 py-2 rounded-md font-medium',
            'bg-gray-200 text-gray-700 dark:bg-gray-600 dark:text-gray-50' =>
                request()->url() === route('posts.index'),
            'text-gray-400 hover:bg-gray-200 hover:text-gray-700 dark:hover:bg-gray-600 dark:hover:text-gray-50' =>
                request()->url() !== route('posts.index'),
        ])
        @if (request()->url() === route('posts.index'))
        aria-current="page"
        @endif
        >
        <i class="bi bi-house-fill"></i><span class="ml-2">全部文章</span>
      </a>
      @foreach ($categories as $category)
        <a
          href="{{ $category->link_with_name }}"
          @class([
              'block px-3 py-2 rounded-md font-medium',
              'bg-gray-200 text-gray-700 dark:bg-gray-600 dark:text-gray-50' =>
                  request()->url() === $category->link_with_name,
              'text-gray-400 hover:bg-gray-200 hover:text-gray-700 dark:hover:bg-gray-600 dark:hover:text-gray-50' =>
                  request()->url() !== $category->link_with_name,
          ])
          @if (request()->url() === $category->link_with_name)
          aria-current="page"
      @endif
      >
      <i class="{{ $category->icon }}"></i><span class="ml-2">{{ $category->name }}</span>
      </a>
      @endforeach
    </div>
  </div>
</div>
