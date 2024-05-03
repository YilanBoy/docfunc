@script
  <script>
    Alpine.data('search', () => ({
      searchBarIsOpen: false,
      openSearchBar() {
        this.searchBarIsOpen = true;
        this.$nextTick(() => {
          this.$refs.searchBar.focus()
        })
      },
      closeSearchBar() {
        this.searchBarIsOpen = false;
      }
    }));
  </script>
@endscript

<div x-data="search">
  {{-- 搜尋按鈕 --}}
  <button
    class="group hidden items-center justify-center rounded-lg bg-gray-200 p-2 text-xl text-gray-500 transition duration-150 dark:bg-gray-600 dark:text-gray-400 xl:flex"
    type="button"
    aria-label="Search"
    x-on:click="openSearchBar"
    x-on:keydown.window.prevent.cmd.k="openSearchBar"
    x-on:keydown.window.prevent.ctrl.k="openSearchBar"
    x-on:keydown.window.escape="closeSearchBar"
  >
    <x-icon.search class="w-5 group-hover:text-gray-900 dark:group-hover:text-gray-50" />

    <div class="ml-2 flex space-x-2 text-base">
      <span class="group-hover:text-gray-900 dark:group-hover:text-gray-50">搜尋</span>

      <div class="shortcut">
        <kbd class="rounded bg-gray-300 px-2 py-1 dark:bg-gray-500 dark:text-gray-200">⌘</kbd>
        <kbd class="rounded bg-gray-300 px-2 py-1 dark:bg-gray-500 dark:text-gray-200">K</kbd>
      </div>
    </div>
  </button>

  {{-- search moodal --}}
  <div
    class="fixed inset-0 z-30 overflow-y-auto"
    role="dialog"
    aria-labelledby="modal-title"
    aria-modal="true"
    x-cloak
    x-show="searchBarIsOpen"
  >
    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">

      {{-- modal --}}
      <div
        class="fixed inset-0 bg-gray-500/75 backdrop-blur transition-opacity"
        x-show="searchBarIsOpen"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        x-description="Background backdrop, show/hide based on modal state."
      ></div>

      {{-- search form --}}
      <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-start sm:p-0">
          <div
            class="mt-16 inline-block w-full max-w-md transition-all"
            x-show="searchBarIsOpen"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-on:click.outside="closeSearchBar"
            x-trap.noscroll="searchBarIsOpen"
          >
            {{-- search form --}}
            <div class="relative">
              <label
                class="hidden"
                for="searchBox"
              >搜尋</label>
              <input
                class="w-full rounded-xl border border-gray-400 bg-gray-50 px-10 py-2 text-xl placeholder-gray-400 outline-none focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-800 dark:text-gray-50 dark:placeholder-white"
                id="searchBox"
                type="text"
                x-ref="searchBar"
                wire:model.live.debounce.500ms="search"
                autocomplete="off"
                placeholder="搜尋文章"
              />

              <div class="absolute left-3 top-3.5 text-lg text-gray-400 dark:text-gray-50">
                <x-icon.search class="w-5" />
              </div>

              <x-icon.animate-spin
                class="absolute right-3 top-3 h-5 w-5 text-gray-700 dark:text-gray-50"
                wire:loading
              />
            </div>

            {{-- 搜尋結果列表 --}}
            @if (strlen($search) >= 2)
              <div
                class="mt-4 w-full rounded-xl bg-gray-50 p-2 ring-1 ring-black ring-opacity-20 dark:bg-gray-800 dark:text-gray-50"
                wire:transition
              >
                @if ($results->count() > 0)
                  <div class="mb-2 flex items-center justify-center border-b-2 border-gray-400 pb-2">
                    <span>搜尋結果</span>
                  </div>

                  <ul>
                    @foreach ($results as $result)
                      <li>
                        <a
                          class="flex items-center rounded-md p-2 text-left hover:bg-gray-200 dark:text-gray-50 dark:hover:bg-gray-600"
                          href="{{ $result->link_with_slug }}"
                          wire:navigate
                        >
                          <x-icon.caret-right class="w-4" />
                          <span class="ml-2">{{ $result->title }}</span>
                        </a>
                      </li>
                    @endforeach
                  </ul>
                @else
                  <div class="flex h-16 items-center justify-center">
                    抱歉...找不到相關文章
                  </div>
                @endif

                {{-- Algolia Logo --}}
                <div class="mt-2 flex w-full items-center justify-center border-t-2 border-gray-400 pt-2">
                  <a
                    href="https://www.algolia.com"
                    target="_blank"
                    rel="nofollow noopener noreferrer"
                  >
                    {{-- Light Mode Algolia Logo --}}
                    <img
                      class="inline-block dark:hidden"
                      src="{{ asset('images/icon/search-by-algolia-light-background.png') }}"
                      alt="Search by Algolia"
                    >

                    {{-- Dark Mode Algolia Logo --}}
                    <img
                      class="hidden dark:inline-block"
                      src="{{ asset('images/icon/search-by-algolia-dark-background.png') }}"
                      alt="Search by Algolia"
                    >
                  </a>
                </div>
              </div>
            @endif

          </div>
        </div>
      </div>

    </div>
  </div>
</div>
