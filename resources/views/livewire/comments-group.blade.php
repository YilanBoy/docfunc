<div class="space-y-6 mt-6">
    @forelse ($comments as $comment)
        {{-- 第一階層留言 --}}
        <x-card class="flex relative group">
            <div class="flex flex-col md:flex-row flex-1">
                {{-- 大頭貼 --}}
                <div class="flex-none">
                    <a href="{{ route('users.index', ['user' => $comment->user_id]) }}">
                        <img src="{{ $comment->user->gravatar() }}" alt="{{ $comment->user->name }}"
                             class="w-14 h-14 rounded-xl hover:ring-4 hover:ring-blue-400">
                    </a>
                </div>

                {{-- 留言 --}}
                <div class="w-full md:mx-4">
                    <div class="text-gray-600 mt-3 sm:mt-0 dark:text-gray-50">
                        {!! nl2br(e($comment->content)) !!}
                    </div>

                    <div class="flex items-center justify-between mt-3">
                        <div class="flex items-center text-sm text-gray-400 space-x-2">
                            <div>{{ $comment->user->name }}</div>
                            <div>&bull;</div>
                            <div>{{ $comment->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                </div>

                {{-- 新增第二階層留言與刪除留言 --}}
                @auth
                    <div class="mt-2 md:mt-0 flex justify-start items-center space-x-2
                    opacity-0 group-hover:opacity-100 transition duration-150">
                        <button
                            x-on:click="
                                $dispatch('set-comment-box-open', true)
                                $dispatch('set-comment-id', {{ $comment->id }})
                                $dispatch('set-comment-to', '回覆{{ $comment->user->name }}')
                                $dispatch('comment-box-focus')
                                disableScroll()
                            "
                            class="w-10 h-10 inline-flex justify-center items-center border border-transparent rounded-md font-semibold text-gray-50
                            bg-blue-600 hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900
                            focus:ring ring-blue-300 transition ease-in-out duration-150"
                        >
                            <i class="bi bi-chat-left-text-fill"></i>
                        </button>

                        @if (in_array(auth()->id(), [$comment->user_id, $authorId]))
                            <button
                                onclick="confirm('您確定要刪除此留言嗎？') || event.stopImmediatePropagation()"
                                wire:click="destroy({{ $comment->id }})"
                                class="w-10 h-10 inline-flex justify-center items-center border border-transparent rounded-md font-semibold text-gray-50
                                bg-red-600 hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900
                                focus:ring ring-red-300 transition ease-in-out duration-150"
                            >
                                <i class="bi bi-trash-fill"></i>
                            </button>
                        @endif
                    </div>
                @endauth
            </div>
        </x-card>

        {{-- 第二階層留言 --}}
        <div class="comment-with-responses">
            <div class="responses space-y-6">
                @forelse ($comment->children as $child)
                    <div class="relative">
                        <x-card class="group flex">
                            <div class="flex flex-col md:flex-row flex-1">
                                {{-- 大頭貼 --}}
                                <div class="flex-none">
                                    <a href="{{ route('users.index', ['user' => $child->user_id]) }}">
                                        <img src="{{ $child->user->gravatar() }}" alt="{{ $child->user->name }}"
                                             class="w-14 h-14 rounded-xl hover:ring-4 hover:ring-blue-400">
                                    </a>
                                </div>

                                {{-- 留言 --}}
                                <div class="w-full md:mx-4">
                                    <div class="text-gray-600 mt-3 sm:mt-0 dark:text-gray-50">
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

                                {{-- 刪除留言 --}}
                                @if (in_array(auth()->id(), [$child->user_id, $authorId]))
                                    <div class="mt-2 md:mt-0 flex justify-start items-center
                                    opacity-0 group-hover:opacity-100 transition duration-150">
                                        <button
                                            onclick="confirm('您確定要刪除此留言嗎？') || event.stopImmediatePropagation()"
                                            wire:click="destroy({{ $child->id }})"
                                            class="w-10 h-10 inline-flex justify-center items-center border border-transparent rounded-md font-semibold text-gray-50
                                            bg-red-600 hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900
                                            focus:ring ring-red-300 transition ease-in-out duration-150"
                                        >
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </div>
                                @endif
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
