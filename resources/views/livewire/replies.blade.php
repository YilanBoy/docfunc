<div class="w-full space-y-6 mt-6">
    {{-- Reply Container --}}
    <div id="post-{{ $post->id }}-replies" class="w-full space-y-6">

        @forelse ($replies as $reply)
            {{-- Reply Container --}}
            <x-card
                id="post-{{ $post->id }}-reply-{{ $reply->id }}"
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

                        <div class="flex items-center justify-between mt-3">
                            <div class="flex items-center text-sm text-gray-400 space-x-2">
                                <div>{{ $reply->user->name }}</div>
                                <div>&bull;</div>
                                <div>{{ $reply->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-2 md:mt-0 flex justify-start items-center space-x-2">
                        @auth
                            <button
                                x-on:click="
                                    replyBoxOpen = true
                                    $nextTick(() => { $refs.replyBox.focus() })
                                "
                                wire:click="$emit('switchReplyId', {{ $reply->id }})"
                                class="w-8 h-8 flex justify-center items-center bg-blue-600 border border-transparent rounded-md font-semibold text-white
                                uppercase tracking-widest hover:bg-blue-500 active:bg-blue-900 focus:outline-none focus:border-blue-900
                                focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150"
                            >
                                <i class="bi bi-chat-left-text-fill"></i>
                            </button>

                            @if (in_array(auth()->id(), [$reply->user_id, $post->user_id]))
                                <button
                                    onclick="confirm('您確定要刪除此回覆嗎？') || event.stopImmediatePropagation()"
                                    wire:click="destroy({{ $reply->id }})"
                                    class="w-8 h-8 flex justify-center items-center bg-red-600 border border-transparent rounded-md font-semibold text-white
                                    uppercase tracking-widest hover:bg-red-500 active:bg-red-900 focus:outline-none focus:border-red-900
                                    focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150"
                                >
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            @endif
                        @endauth
                    </div>
                </div>
            </x-card>

            {{-- 子回覆 --}}
            <div class="reply-with-responses">
                <div class="responses space-y-6">
                    @forelse ($reply->children as $child)
                        <div class="relative">
                            <x-card
                                id="post-{{ $post->id }}-reply-{{ $child->id }}"
                                class="flex"
                            >
                                <div class="flex flex-col md:flex-row flex-1">
                                    {{-- 大頭貼 --}}
                                    <div class="flex-none">
                                        <a href="{{ route('users.show', ['user' => $child->user_id]) }}">
                                            <img src="{{ $child->user->gravatar() }}" alt="{{ $child->user->name }}"
                                            class="w-14 h-14 rounded-xl hover:ring-4 hover:ring-blue-400">
                                        </a>
                                    </div>

                                    {{-- 留言 --}}
                                    <div class="w-full md:mx-4">
                                        <div class="text-gray-600 mt-3 sm:mt-0 dark:text-white">
                                            {!! nl2br(e($child->content)) !!}
                                        </div>

                                        <div class="flex items-center justify-between mt-3">
                                            <div class="flex items-center text-sm text-gray-400 space-x-2">
                                                <div>{{ $child->user->name }}</div>
                                                <div>&bull;</div>
                                                <div>{{ $child->created_at->diffForHumans() }}</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-2 md:mt-0 flex justify-start items-center">
                                        @if (in_array(auth()->id(), [$child->user_id, $post->user_id]))
                                            <button
                                                onclick="confirm('您確定要刪除此回覆嗎？') || event.stopImmediatePropagation()"
                                                wire:click="destroy({{ $child->id }})"
                                                class="w-8 h-8 flex justify-center items-center bg-red-600 border border-transparent rounded-md font-semibold text-white
                                                uppercase tracking-widest hover:bg-red-500 active:bg-red-900 focus:outline-none focus:border-red-900
                                                focus:ring ring-red-300 disabled:opacity-25 transition ease-in-out duration-150"
                                            >
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </x-card>
                        </div>
                    @empty
                    @endforelse
                </div>
            </div>
        @empty
        @endforelse
    </div>
</div>
