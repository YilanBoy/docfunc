<div
    x-data="{ searchBoxOpen: false }"
>
    {{-- 搜尋按鈕 --}}
    <button
        x-on:click="
            searchBoxOpen = !searchBoxOpen
            $nextTick(() => { $refs.searchBox.focus() })
            disableScroll()
        "
        type="button"
        class="w-10 h-10 flex justify-center items-center text-2xl rounded-lg
        text-gray-400 hover:text-gray-700 hover:bg-gray-200 transition duration-150"
    >
        <i class="bi bi-search"></i>
    </button>

    {{-- 搜尋 Modal --}}
    <div
        x-cloak
        x-show="searchBoxOpen"
        x-on:keydown.window.escape="
            searchBoxOpen = false
            enableScroll()
        "
        class="fixed z-20 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true"
    >
        <div
            x-cloak
            x-show="searchBoxOpen"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0"
        >
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <div
                x-cloak
                x-show="searchBoxOpen"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-on:click.outside="
                    searchBoxOpen = false
                    enableScroll()
                "
                class="inline-block transform transition-all mt-16 max-w-md w-full"
            >
                {{-- 搜尋欄 --}}
                <div class="relative">
                    <input
                        x-ref="searchBox"
                        type="text"
                        wire:model.debounce.500ms="search"
                        autocomplete="off"
                        placeholder="搜尋文章"
                        class="outline-none w-full rounded-xl text-xl bg-gray-50 placeholder-gray-400 border border-gray-400
                        focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 py-2 px-10
                        dark:bg-gray-700 dark:placeholder-white dark:text-gray-50"
                    />

                    <div class="absolute top-2.5 left-3.5 text-lg text-gray-400 dark:text-gray-50">
                        <i class="bi bi-search"></i>
                    </div>

                    <div
                        wire:loading
                        class="absolute top-3 right-0"
                    >
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-gray-700 dark:text-gray-50" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </div>

                {{-- 搜尋結果列表 --}}
                @if (strlen($search) >= 2)
                    <div
                        class="w-full mt-4 bg-gray-50 p-2 shadow-md rounded-xl ring-1 ring-black ring-opacity-20
                        dark:bg-gray-700 dark:text-gray-50"
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
                                            class="flex text-left rounded-md p-2 hover:bg-gray-200
                                            dark:text-gray-50 dark:hover:bg-gray-600"
                                        >
                                            <i class="bi bi-caret-right-fill"></i><span class="ml-2">{{ $result->title }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="h-16 flex justify-center items-center">
                                抱歉...找不到相關文章
                            </div>
                        @endif

                        {{-- Algolia Logo --}}
                        <div class="w-full flex justify-center items-center border-t-2 border-gray-400 pt-2 mt-2">
                            <a target="_blank" rel="nofollow noopener noreferrer" href="https://www.algolia.com">
                                {{-- Light Mode Algolia Logo --}}
                                <img src="/images/icon/search-by-algolia-light-background.png" alt="Search by Algolia"
                                class="inline-block dark:hidden">

                                {{-- Dark Mode Algolia Logo --}}
                                <img src="/images/icon/search-by-algolia-dark-background.png" alt="Search by Algolia"
                                class="hidden dark:inline-block">
                            </a>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
