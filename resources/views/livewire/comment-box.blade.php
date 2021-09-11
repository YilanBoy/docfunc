<div
    x-data="{
        commentBoxOpen: false,
        commentId: @entangle('commentId'),
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

            @auth
                <button
                    x-on:click="
                        commentBoxOpen = true
                        commentId = null
                        commentTo = '回覆此文章'
                        $nextTick(() => { $refs.commentBox.focus() })
                        disableScroll()
                    "
                    type="button"
                    class="group relative h-12 w-40 inline-flex rounded-lg border border-blue-600 focus:outline-none"
                >
                    <span
                        class="absolute inset-0 inline-flex items-center justify-center self-stretch px-6 text-gray-50 text-center font-medium bg-blue-600
                        rounded-lg ring-1 ring-blue-600 ring-offset-1 ring-offset-blue-600 transform transition-transform
                        group-hover:-translate-y-2 group-hover:-translate-x-2"
                    >
                        <i class="bi bi-chat-left-text-fill"></i><span class="ml-2">新增留言</span>
                    </span>
                </button>
            @endauth
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
            class="fixed z-20 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true"
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
                class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0"
            >
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

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
                    class="inline-block align-bottom bg-gray-50 rounded-lg text-left overflow-hidden shadow-xl
                    transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full
                    dark:bg-gray-700 p-5"
                >
                    {{-- 留言提示 --}}

                    <div class="text-gray-700 dark:text-gray-50">
                        <div x-text="commentTo"></div>
                    </div>

                    <div class="sm:flex sm:items-start mt-5">
                        <textarea
                            x-ref="commentBox"
                            wire:model.debounce.500ms="content"
                            placeholder="分享你的想法 ~"
                            rows="5"
                            class="form-textarea w-full rounded-md shadow-sm border border-gray-300
                            focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50
                            dark:bg-gray-600 dark:text-gray-50 dark:placeholder-white"
                        ></textarea>
                    </div>

                    <div class="sm:flex sm:flex-row-reverse mt-5">
                        <button
                            x-on:click="
                                commentBoxOpen = false
                                enableScroll()
                            "
                            wire:click="store()"
                            type="button"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2
                            bg-blue-600 text-base font-medium text-gray-50 hover:bg-blue-700
                            focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            留言
                        </button>
                        <button
                            x-on:click="
                                commentBoxOpen = false
                                enableScroll()
                            "
                            type="button"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2
                            bg-gray-50 text-base font-medium text-gray-700 hover:bg-gray-50
                            focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm
                            dark:bg-gray-500 dark:text-gray-50 dark:hover:bg-gray-600"
                        >
                            取消
                        </button>

                        <div class="flex-1 flex justify-start">
                            <div class="flex justify-center items-center">
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

