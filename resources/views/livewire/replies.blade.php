<div class="w-full xl:w-2/3">
    {{-- Reply --}}
    @if (auth()->check())
        <div class="w-full shadow-md bg-white rounded-xl ring-1 ring-black ring-opacity-20 p-5 mt-6">
            <textarea
                wire:model.debounce.500ms="content"
                id="content"
                placeholder="分享你的評論~"
                class="w-full h-24 rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
            ></textarea>

            <div class="flex justify-between mt-2">
                <div class="flex justify-center items-center">
                    @error('content') <span class="text-red-700">{{ $message }}</span> @enderror
                </div>

                <button
                    wire:click="store"
                    class="text-white font-bold bg-blue-600 hover:bg-blue-800 active:bg-blue-600 rounded-md text-center py-2 px-4 shadow-lg"
                >
                    <i class="bi bi-chat-left-text-fill"></i><span class="ml-2">回覆</span>
                </button>
            </div>

        </div>
    @endif

    {{-- Reply Container --}}
    <div id="post-{{ $post->id }}-replies-container" class="w-full space-y-6 mt-6">

        @forelse ($replies as $reply)
            {{-- Reply Container --}}
            <div id="post-{{ $post->id }}-reply-card-{{ $reply->id }}" class="flex relative bg-white rounded-xl ring-1 ring-black ring-opacity-20 shadow-md">

                <div class="flex flex-col md:flex-row flex-1 p-4">
                    {{-- 大頭貼 --}}
                    <div class="flex-none">
                        <a href="{{ route('users.show', ['user' => $reply->user_id]) }}">
                            <img src="{{ $reply->user->gravatar() }}" alt="{{ $reply->user->name }}"
                            class="w-14 h-14 rounded-xl hover:ring-4 hover:ring-blue-400">
                        </a>
                    </div>
                    {{-- 留言 --}}
                    <div class="w-full md:mx-4">
                        <div class="text-gray-600 mt-3 sm:mt-0">
                            {!! nl2br(e($reply->content)) !!}
                        </div>

                        <div class="flex items-center justify-between mt-6">
                            <div class="flex items-center text-sm text-gray-400 space-x-2">
                                <div class="text-gray-900">{{ $reply->user->name }}</div>
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
                                            class="bg-gray-100 hover:bg-gray-200 border rounded-full h-7 transition duration-150 ease-in py-2 px-3"
                                            aria-expanded="false" aria-haspopup="true"
                                        >
                                            <svg fill="currentColor" width="24" height="6">
                                                <path d="M2.97.061A2.969 2.969 0 000 3.031 2.968 2.968 0 002.97 6a2.97 2.97 0 100-5.94zm9.184 0a2.97 2.97 0 100 5.939 2.97 2.97
                                                0 100-5.939zm8.877 0a2.97 2.97 0 10-.003 5.94A2.97 2.97 0 0021.03.06z" style="color: rgba(163, 163, 163, .5)">
                                            </svg>
                                        </button>
                                    </div>

                                    <div
                                        x-cloak
                                        x-show.transition.duration.100ms.top.left="deleteMenuIsOpen"
                                        class="origin-top-right absolute right-0 z-20 p-2 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-20"
                                        role="menu" aria-orientation="vertical" tabindex="-1"
                                    >
                                        <button
                                            tabindex="-1"
                                            onclick="confirm('您確定要刪除此回覆嗎？') || event.stopImmediatePropagation()"
                                            wire:click.prevent="destroy({{ $reply->id }})"
                                            class="flex items-start w-full px-4 py-2 rounded-md text-gray-700 hover:bg-gray-200 active:bg-gray-100"
                                        >
                                            <i class="bi bi-trash-fill"></i><span class="ml-2">刪除</span>
                                        </button>
                                    </div>
                                </div>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        @empty
        @endforelse
    </div>
</div>
