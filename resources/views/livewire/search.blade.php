<div
    x-data="{ showSearchBox: false }"
    class="relative"
>
    {{-- 搜尋按鈕 --}}
    <button
        x-on:click="
            showSearchBox = !showSearchBox
            $nextTick(() => { $refs.searchBox.focus() })
        "
        type="button"
        class="w-10 h-10 flex justify-center items-center text-2xl rounded-lg
        text-gray-400 hover:text-gray-700 hover:bg-gray-200 transition duration-150"
    >
        <i class="bi bi-search"></i>
    </button>

    <div
        x-cloak
        x-show="showSearchBox"
        x-transition.origin.left
        x-on:click.outside="showSearchBox = false"
        x-on:keydown.escape.window="showSearchBox = false"
        class="absolute top-0 left-16 w-60"
    >
        <div class="relative">
            {{-- 搜尋輸入框 --}}
            <div class="relative">
                <input
                    x-ref="searchBox"
                    type="text"
                    wire:model.debounce.500ms="search"
                    autocomplete="off"
                    placeholder="搜尋文章"
                    class="outline-none w-full rounded-xl bg-white placeholder-gray-400 border border-gray-400
                    focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 py-2 px-4
                    dark:bg-gray-500 dark:placeholder-white dark:text-white"
                />

                <div
                    wire:loading
                    class="absolute top-3 right-0"
                >
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-gray-700 dark:text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            </div>

            {{-- 搜尋結果 --}}
            @if (strlen($search) >= 2)
                <div
                    class="absolute w-96 text-sm mt-2 z-10 bg-white p-2 shadow-md rounded-xl ring-1 ring-black ring-opacity-20
                    dark:bg-gray-500 dark:text-white"
                >
                    @if ($results->count() > 0)
                        <div class="flex justify-center items-center border-b-2 border-gray-400 pb-2 mb-2">
                            <span>搜尋結果</span>
                        </div>

                        <ul>
                            @foreach ($results as $result)
                                <li>
                                    <a
                                        href="{{ $result->link_with_slug }}"
                                        class="block text-black rounded-md p-2 hover:bg-gray-200
                                        dark:text-white dark:hover:bg-gray-400">
                                        {{ $result->title }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="h-16 flex justify-center items-center">
                            <span class="ml-2">沒有「{{ $search }}」的搜尋結果</span>
                        </div>
                    @endif

                    {{-- Algolia Logo --}}
                    <div class="w-full flex justify-center items-center border-t-2 border-gray-400 pt-2 mt-2">
                        <a target="_blank" rel="nofollow noopener noreferrer" href="https://www.algolia.com">
                            {{-- Light Mode Logo --}}
                            <img src="/images/icon/search-by-algolia-light-background.png" alt="Search by Algolia"
                            class="inline-block dark:hidden">

                            {{-- Dark Mode Logo --}}
                            <img src="/images/icon/search-by-algolia-dark-background.png" alt="Search by Algolia"
                            class="hidden dark:inline-block">
                        </a>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
