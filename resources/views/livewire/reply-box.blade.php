<div x-data="{ replyBoxOpen : false }" class="w-full xl:w-2/3 mt-6">
    @auth
        {{-- Open Reply Box Modal--}}
        <div class="flex justify-end">
            <button
                @click="replyBoxOpen = true"
                wire:click="switchReplyId(null)"
                type="button"
                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-md px-4 py-2
                bg-blue-600 text-base font-medium text-white hover:bg-blue-700
                focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:w-auto"
            >
            <i class="bi bi-chat-left-text-fill"></i><span class="ml-2 ">回覆文章</span>
            </button>
        </div>

        {{-- Reply Box Modal --}}
        <div
            x-cloak
            x-show="replyBoxOpen"
            @keydown.window.escape="replyBoxOpen = false"
            class="fixed z-20 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true"
        >
            <div
                x-cloak
                x-show="replyBoxOpen"
                x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0"
            >
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

                <!-- This element is to trick the browser into centering the modal contents. -->
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div
                    x-cloak
                    x-show="replyBoxOpen"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl
                    transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full
                    dark:bg-gray-700 p-5"
                >

                    <div class="sm:flex sm:items-start">
                        <textarea
                            wire:model.debounce.500ms="content"
                            placeholder="分享你的評論~"
                            rows="5"
                            class="form-textarea w-full rounded-md shadow-sm border border-gray-300
                            focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50
                            dark:bg-gray-600 dark:text-white dark:placeholder-white"
                        ></textarea>
                    </div>

                    <div class="mt-5 sm:flex sm:flex-row-reverse">
                        <button
                            @click="replyBoxOpen = false"
                            wire:click="store()"
                            type="button"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2
                            bg-blue-600 text-base font-medium text-white hover:bg-blue-700
                            focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            回覆
                        </button>
                        <button
                            @click="replyBoxOpen = false"
                            type="button"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2
                            bg-white text-base font-medium text-gray-700 hover:bg-gray-50
                            focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm
                            dark:bg-gray-500 dark:text-white dark:hover:bg-gray-600"
                        >
                            取消
                        </button>

                        <div class="flex-1 flex justify-start">
                            <div class="flex justify-center items-center">
                                @error('content') <span class="text-red-600">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endauth

    @livewire('replies', ['post' => $post])
</div>

