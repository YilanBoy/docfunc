<div
    x-data="{
        commentBoxOpen: false,
        commentId: $wire.entangle('commentId'),
        commentTo: ''
    }"
    class="w-full xl:w-2/3"
>

        <div class="flex justify-between mt-6">
            {{-- 顯示留言數目 --}}
            <span class="flex items-center dark:text-gray-50">
                <i class="bi bi-chat-square-text-fill"></i>
                <span class="ml-2">{{ $commentCount }} 則留言</span>
            </span>

            <button
                x-on:click="
                    commentBoxOpen = true
                    commentId = null
                    commentTo = '回覆此文章'
                    $nextTick(() => { $refs.commentBox.focus() })
                    disableScroll()
                "
                wire:click="authCheck"
                type="button"
                class="relative inline-flex w-40 h-12 border border-blue-600 rounded-lg group focus:outline-none"
            >
                <span
                    class="absolute inset-0 inline-flex items-center self-stretch justify-center px-6 font-medium text-center transition-transform transform bg-blue-600 rounded-lg text-gray-50 ring-1 ring-blue-600 ring-offset-1 ring-offset-blue-600 group-hover:-translate-y-2 group-hover:-translate-x-2"
                >
                    <i class="bi bi-chat-left-text-fill"></i><span class="ml-2">新增留言</span>
                </span>
            </button>
        </div>

    @auth
        {{-- 留言表單 Modal --}}
        <div
            x-cloak
            x-show="commentBoxOpen"
            x-on:keydown.window.escape="
                commentBoxOpen = false
                enableScroll()
            "
            class="fixed inset-0 z-20 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true"
        >
            <div
                x-cloak
                x-show="commentBoxOpen"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0"
            >
                <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true"></div>

                {{-- This element is to trick the browser into centering the modal contents. --}}
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                {{-- 留言表單 --}}
                <div
                    x-cloak
                    x-show="commentBoxOpen"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-on:click.outside="
                        commentBoxOpen = false
                        enableScroll()
                    "
                    class="inline-block p-5 overflow-hidden text-left align-bottom transition-all transform rounded-lg shadow-xl bg-gray-50 sm:my-8 sm:align-middle sm:max-w-lg sm:w-full dark:bg-gray-700"
                >
                    {{-- 留言提示 --}}

                    <div class="text-gray-700 dark:text-gray-50">
                        <div x-text="commentTo"></div>
                    </div>

                    <div class="mt-5 sm:flex sm:items-start">
                        <textarea
                            x-ref="commentBox"
                            wire:model.debounce.500ms="content"
                            placeholder="分享你的想法 ~"
                            rows="5"
                            class="w-full border border-gray-300 rounded-md shadow-sm form-textarea focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-600 dark:text-gray-50 dark:placeholder-white"
                        ></textarea>
                    </div>

                    <div class="mt-5 sm:flex sm:flex-row-reverse">
                        <button
                            x-on:click="
                                commentBoxOpen = false
                                enableScroll()
                            "
                            wire:click="store()"
                            type="button"
                            class="inline-flex justify-center w-full px-4 py-2 text-base font-medium bg-blue-600 border border-transparent rounded-md shadow-sm text-gray-50 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            留言
                        </button>
                        <button
                            x-on:click="
                                commentBoxOpen = false
                                enableScroll()
                            "
                            type="button"
                            class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 border border-gray-300 rounded-md shadow-sm bg-gray-50 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm dark:bg-gray-500 dark:text-gray-50 dark:hover:bg-gray-600"
                        >
                            取消
                        </button>

                        <div class="flex justify-start flex-1">
                            <div class="flex items-center justify-center">
                                @error('content') <span class="text-red-400">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endauth

    @livewire('comments', ['post' => $post])
</div>

