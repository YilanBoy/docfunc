<div x-data="{ searchBoxOpen: false }">
  {{-- 搜尋按鈕 --}}
  <button
    x-on:click="
      searchBoxOpen = !searchBoxOpen
      $nextTick(() => { $refs.searchBox.focus() })
    "
    type="button"
    class="flex h-10 w-10 items-center justify-center rounded-lg text-xl text-gray-400 transition duration-150 hover:bg-gray-200 hover:text-gray-700 dark:hover:bg-gray-600 dark:hover:text-gray-50"
  >
    <i class="bi bi-search"></i>
  </button>

  {{-- search moodal --}}
  <div
    x-cloak
    x-show="searchBoxOpen"
    x-on:keydown.window.escape="searchBoxOpen = false"
    class="fixed inset-0 z-30 overflow-y-auto"
    aria-labelledby="modal-title"
    role="dialog"
    aria-modal="true"
  >
    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">

      {{-- modal --}}
      <div
        x-show="searchBoxOpen"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        x-description="Background backdrop, show/hide based on modal state."
        class="fixed inset-0 bg-gray-500/75 backdrop-blur transition-opacity"
      ></div>

      {{-- search form --}}
      <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-start sm:p-0">
          <div
            x-show="searchBoxOpen"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-on:click.outside="searchBoxOpen = false"
            x-trap.noscroll="searchBoxOpen"
            class="mt-16 inline-block w-full max-w-md transition-all"
          >
            {{-- search form --}}
            <div class="relative">
              <label
                for="searchBox"
                class="hidden"
              >搜尋</label>
              <input
                id="searchBox"
                x-ref="searchBox"
                type="text"
                wire:model.debounce.500ms="search"
                autocomplete="off"
                placeholder="搜尋文章"
                class="w-full rounded-xl border border-gray-400 bg-gray-50 px-10 py-2 text-xl placeholder-gray-400 outline-none focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:text-gray-50 dark:placeholder-white"
              />

              <div class="absolute left-3.5 top-2.5 text-lg text-gray-400 dark:text-gray-50">
                <i class="bi bi-search"></i>
              </div>

              <div
                wire:loading
                class="absolute right-3 top-3"
              >
                <svg
                  class="h-5 w-5 animate-spin text-gray-700 dark:text-gray-50"
                  xmlns="http://www.w3.org/2000/svg"
                  fill="none"
                  viewBox="0 0 24 24"
                >
                  <circle
                    class="opacity-25"
                    cx="12"
                    cy="12"
                    r="10"
                    stroke="currentColor"
                    stroke-width="4"
                  ></circle>
                  <path
                    class="opacity-75"
                    fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                  ></path>
                </svg>
              </div>
            </div>

            {{-- 搜尋結果列表 --}}
            @if (strlen($search) >= 2)
              <div
                class="mt-4 w-full rounded-xl bg-gray-50 p-2 shadow-md ring-1 ring-black ring-opacity-20 dark:bg-gray-700 dark:text-gray-50"
              >
                @if ($results->count() > 0)
                  <div class="mb-2 flex items-center justify-center border-b-2 border-gray-400 pb-2">
                    <span>搜尋結果</span>
                  </div>

                  <ul>
                    @foreach ($results as $result)
                      <li>
                        <a
                          href="{{ $result->link_with_slug }}"
                          class="flex rounded-md p-2 text-left hover:bg-gray-200 dark:text-gray-50 dark:hover:bg-gray-600"
                        >
                          <i class="bi bi-caret-right-fill"></i><span class="ml-2">{{ $result->title }}</span>
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
                    target="_blank"
                    rel="nofollow noopener noreferrer"
                    href="https://www.algolia.com"
                  >
                    {{-- Light Mode Algolia Logo --}}
                    <img
                      src="{{ asset('images/icon/search-by-algolia-light-background.png') }}"
                      alt="Search by Algolia"
                      class="inline-block dark:hidden"
                    >

                    {{-- Dark Mode Algolia Logo --}}
                    <img
                      src="{{ asset('images/icon/search-by-algolia-dark-background.png') }}"
                      alt="Search by Algolia"
                      class="hidden dark:inline-block"
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
