{{-- 會員文章 --}}
<div class="space-y-6">
    {{-- 文章列表 --}}
    @forelse ($posts as $post)
        <x-card
            x-data="cardLink"
            x-on:click="postCardLink($event, $refs)"
            class="flex flex-col md:flex-row justify-between hover:shadow-xl
            transform hover:-translate-x-2 transition duration-150 ease-in cursor-pointer"
        >
            {{-- 文章 --}}
            <div class="w-full flex flex-col justify-between">
                @if ($post->trashed())
                    <span class="text-red-400">此文章已被標記為刪除狀態！</span>
                @endif

                {{-- 文章標題 --}}
                <span class="text-xl font-semibold mt-2 md:mt-0 dark:text-gray-50">
                    <a
                        x-ref="postLink"
                        href="{{ $post->trashed() ? '#' : $post->link_with_slug }}"
                        class="fancy-link"
                    >{{ $post->title }}</a>
                </span>

                {{-- 文章相關資訊 --}}
                <div class="flex items-center text-sm text-gray-400 mt-2 space-x-2">
                    {{-- 文章分類資訊 --}}
                    <div>
                        <a
                            href="{{ $post->category->link_with_name }}"
                            title="{{ $post->category->name }}"
                            class="hover:text-gray-700 dark:hover:text-gray-50"
                        >
                            <i class="{{ $post->category->icon }}"></i><span class="ml-2">{{ $post->category->name }}</span>
                        </a>
                    </div>
                    <div>&bull;</div>
                    {{-- 文章發布時間 --}}
                    <div>
                        <a
                            href="{{ $post->trashed() ? '#' : $post->link_with_slug }}"
                            class="hover:text-gray-700 dark:hover:text-gray-50"
                            title="文章發布於：{{ $post->created_at }}"
                        >
                            <i class="bi bi-clock-fill"></i><span class="ml-2">{{ $post->created_at->diffForHumans() }}</span>
                        </a>
                    </div>
                    <div>&bull;</div>
                    <div>
                        {{-- 文章留言數 --}}
                        <a class="hover:text-gray-700 dark:hover:text-gray-50"
                        href="{{ $post->trashed() ? '#' : $post->link_with_slug . '#post-' . $post->id . '-comments' }}">
                            <i class="bi bi-chat-square-text-fill"></i><span class="ml-2">{{ $post->comment_count }}</span>
                        </a>
                    </div>
                </div>
            </div>

            @if (auth()->id() === $post->user_id)
                @if ($post->trashed())
                    {{-- 還原文章隱藏表單 --}}
                    <form
                        id="restore-post-{{ $post->id }}"
                        action="{{ route('posts.restore', ['id' => $post->id]) }}"
                        method="POST"
                        class="hidden"
                    >
                        @csrf
                    </form>

                    {{-- 完全刪除隱藏表單 --}}
                    <form
                        id="force-delete-post-{{ $post->id }}"
                        action="{{ route('posts.forceDelete', ['id' => $post->id]) }}"
                        method="POST"
                        class="hidden"
                    >
                        @csrf
                        @method('DELETE')
                    </form>

                    <div class="flex items-center mt-2 md:mt-0 space-x-2">
                        {{-- 還原文章 --}}
                        <button
                            x-on:click.stop="
                                if (confirm('您確定恢復此文章嗎？')) {
                                    document.getElementById('restore-post-{{ $post->id }}').submit()
                                }
                            "
                            type="button"
                            class="w-10 h-10 inline-flex justify-center items-center border border-transparent rounded-md font-semibold text-gray-50
                            bg-blue-600 hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900
                            focus:ring ring-blue-300 transition ease-in-out duration-150"
                        >
                            <i class="bi bi-file-earmark-check-fill"></i>
                        </button>

                        {{-- 完全刪除 --}}
                        <button
                            x-on:click.stop="
                                if(confirm('您確定要完全刪除此文章嗎？（此動作無法復原）')) {
                                    document.getElementById('force-delete-post-{{ $post->id }}').submit()
                                }
                            "
                            type="button"
                            class="w-10 h-10 inline-flex justify-center items-center border border-transparent rounded-md font-semibold text-gray-50
                            bg-red-600 hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900
                            focus:ring ring-red-300 transition ease-in-out duration-150"
                        >
                            <i class="bi bi-trash-fill"></i>
                        </button>
                    </div>
                @else
                    {{-- 軟刪除隱藏表單 --}}
                    <form
                        id="delete-post-{{ $post->id }}"
                        action="{{ route('posts.destroy', ['post' => $post->id]) }}"
                        method="POST"
                        class="hidden"
                    >
                        @csrf
                        @method('DELETE')
                    </form>

                    <div class="flex items-center mt-2 md:mt-0 space-x-2">
                        {{-- 編輯文章 --}}
                        <a
                            href="{{ route('posts.edit', ['post' => $post->id]) }}"
                            class="w-10 h-10 inline-flex justify-center items-center border border-transparent rounded-md font-semibold text-gray-50
                            bg-green-600 hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900
                            focus:ring ring-green-300 transition ease-in-out duration-150"
                        >
                            <span class="transform group-hover:scale-125 group-hover:-rotate-45 transition duration-150 ease-in">
                                <i class="bi bi-pencil-fill"></i>
                            </span>
                        </a>

                        {{-- 軟刪除 --}}
                        <button
                            x-on:click.prevent.stop="
                                if (confirm('您確定標記此文章為刪除狀態嗎？（時間內還可以恢復）'))
                                {
                                    document.getElementById('delete-post-{{ $post->id }}').submit()
                                }
                            "
                            type="button"
                            class="w-10 h-10 inline-flex justify-center items-center border border-transparent rounded-md font-semibold text-gray-50
                            bg-yellow-600 hover:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:border-yellow-900
                            focus:ring ring-yellow-300 transition ease-in-out duration-150"
                        >
                            <i class="bi bi-file-earmark-x-fill"></i>
                        </button>
                    </div>
                @endif
            @endif
        </x-card>

    @empty
        <x-card class="w-full h-36 flex justify-center items-center
        transform hover:-translate-x-2 transition duration-150 ease-in hover:shadow-xl
        dark:text-gray-50">
            <span>目前沒有文章，有沒有什麼事情想要分享呢？</span>
        </x-card>
    @endforelse

    <div>
        {{ $posts->onEachSide(1)->withQueryString()->links() }}
    </div>
</div>
