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

                    <div class="mt-2 md:mt-0 flex justify-start items-center space-x-3">
                        @auth
                            <button
                                x-on:click="
                                    replyBoxOpen = true
                                    $nextTick(() => { $refs.replyBox.focus() })
                                "
                                @click="replyId = {{ $reply->id }}"
                                class="group relative w-10 h-10 inline-flex rounded-md border border-blue-600 focus:outline-none"
                            >
                                <span class="absolute inset-0 inline-flex items-center justify-center self-stretch text-white text-center font-medium bg-blue-600
                                rounded-md ring-1 ring-blue-600 ring-offset-1 ring-offset-blue-600 transform transition-transform
                                group-hover:-translate-y-2 group-hover:-translate-x-2 group-active:-translate-y-0 group-active:-translate-x-0">
                                    <i class="bi bi-chat-left-text-fill"></i>
                                </span>
                            </button>

                            @if (in_array(auth()->id(), [$reply->user_id, $post->user_id]))
                                <button
                                    onclick="confirm('您確定要刪除此回覆嗎？') || event.stopImmediatePropagation()"
                                    wire:click="destroy({{ $reply->id }})"
                                    class="group relative w-10 h-10 inline-flex rounded-md border border-red-600 focus:outline-none"
                                >
                                    <span class="absolute inset-0 inline-flex items-center justify-center self-stretch text-white text-center font-medium bg-red-600
                                    rounded-md ring-1 ring-red-600 ring-offset-1 ring-offset-red-600 transform transition-transform
                                    group-hover:-translate-y-2 group-hover:-translate-x-2 group-active:-translate-y-0 group-active:-translate-x-0">
                                        <i class="bi bi-trash-fill"></i>
                                    </span>
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
                                                class="group relative w-10 h-10 inline-flex rounded-md border border-red-600 focus:outline-none"
                                            >
                                                <span class="absolute inset-0 inline-flex items-center justify-center self-stretch text-white text-center font-medium bg-red-600
                                                rounded-md ring-1 ring-red-600 ring-offset-1 ring-offset-red-600 transform transition-transform
                                                group-hover:-translate-y-2 group-hover:-translate-x-2 group-active:-translate-y-0 group-active:-translate-x-0">
                                                    <i class="bi bi-trash-fill"></i>
                                                </span>
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
