<div class="w-full xl:w-2/3 space-y-6">
    {{-- Reply --}}
    @if (auth()->check())
        <x-card class="w-full mt-6">
            <textarea
                wire:model.debounce.500ms="content"
                id="content"
                placeholder="分享你的評論~"
                class="outline-none p-2 h-32 w-full rounded-md shadow-sm border border-gray-300
                focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50
                dark:bg-gray-500 dark:text-white dark:placeholder-white"
            ></textarea>

            <div class="flex justify-between mt-2">
                <div class="flex justify-center items-center">
                    @error('content') <span class="text-red-600">{{ $message }}</span> @enderror
                </div>

                <button
                    wire:click="store"
                    class="inline-flex justify-center items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white
                    uppercase tracking-widest hover:bg-blue-500 active:bg-blue-900 focus:outline-none focus:border-blue-900
                    focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150"
                >
                    <i class="bi bi-chat-left-text-fill"></i><span class="ml-2">回覆</span>
                </button>
            </div>

        </x-card>
    @endif

    <div>
        {{ $replies->onEachSide(1)->withQueryString()->links() }}
    </div>

    {{-- Reply Container --}}
    <div id="post-{{ $post->id }}-replies-container" class="w-full space-y-6 mt-6">

        @forelse ($replies as $reply)
            {{-- Reply Container --}}
            <x-card
                id="post-{{ $post->id }}-reply-card-{{ $reply->id }}"
                class="flex relative"
            >
                <div class="flex flex-col md:flex-row flex-1">
                    {{-- 大頭貼 --}}
                    <div class="flex-none">
                        <a href="{{ route('users.show', ['user' => $reply->user_id]) }}">
                            <img src="{{ $reply->user->gravatar() }}" alt="{{ $reply->user->name }}"
                            class="w-14 h-14 rounded-xl hover:ring-4 hover:ring-blue-400">
                        </a>
                    </div>

                    {{-- 留言 --}}
                    <div class="w-full md:mx-4">
                        <div class="text-gray-600 mt-3 sm:mt-0 dark:text-white">
                            {!! nl2br(e($reply->content)) !!}
                        </div>

                        <div class="flex items-center justify-between mt-6">
                            <div class="flex items-center text-sm text-gray-400 space-x-2">
                                <div>{{ $reply->user->name }}</div>
                                <div>&bull;</div>
                                <div>{{ $reply->created_at->diffForHumans() }}</div>
                            </div>

                            @can('destroy', $reply)
                                <div
                                    x-data="{ deleteMenuIsOpen : false }"
                                    class="relative"
                                >
                                    <div>
                                        <button
                                            x-on:click="deleteMenuIsOpen = ! deleteMenuIsOpen"
                                            x-on:click.away="deleteMenuIsOpen = false"
                                            x-on:keydown.escape.window="deleteMenuIsOpen = false"
                                            type="button"
                                            class="text-2xl text-gray-400 hover:text-gray-700 focus:text-gray-700
                                            dark:hover:text-white dark:focus:text-white"
                                            aria-expanded="false" aria-haspopup="true"
                                        >
                                            <i class="bi bi-three-dots"></i>
                                        </button>
                                    </div>

                                    <div
                                        x-cloak
                                        x-show.transition.duration.100ms.top.left="deleteMenuIsOpen"
                                        class="origin-top-right absolute right-0 z-20 p-2 mt-2 w-48 rounded-md shadow-lg bg-white text-gray-700 ring-1 ring-black ring-opacity-20
                                        dark:bg-gray-600 dark:text-white"
                                        role="menu" aria-orientation="vertical" tabindex="-1"
                                    >
                                        <button
                                            tabindex="-1"
                                            onclick="confirm('您確定要刪除此回覆嗎？') || event.stopImmediatePropagation()"
                                            wire:click.prevent="destroy({{ $reply->id }})"
                                            class="flex items-start w-full px-4 py-2 rounded-md hover:bg-gray-200
                                            dark:hover:bg-gray-500"
                                        >
                                            <i class="bi bi-trash-fill"></i><span class="ml-2">刪除</span>
                                        </button>
                                    </div>
                                </div>
                            @endcan
                        </div>
                    </div>
                </div>
            </x-card>
        @empty
        @endforelse
    </div>
</div>
