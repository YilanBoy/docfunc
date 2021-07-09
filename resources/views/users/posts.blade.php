{{-- 會員發布文章區塊 --}}
@forelse ($posts as $post)
    <div
        x-data
        x-on:click="
            const clicked = $event.target;
            const target = clicked.tagName.toLowerCase();
            const ignores = ['a', 'button'];

            if (!ignores.includes(target)) {
                clicked.closest('.posts-container').querySelector('.post-link').click();
            }
        "
        class="posts-container flex flex-col md:flex-row justify-between p-4 shadow-md hover:shadow-xl bg-white rounded-xl
        transform hover:-translate-x-2 transition duration-150 ease-in cursor-pointer
        @if ($post->trashed()) ring-2 ring-red-500 @else ring-1 ring-black ring-opacity-20 @endif"
    >
        {{-- 文章 --}}
        <div class="w-full flex flex-col justify-between">
            @if ($post->trashed())
                <span class="text-red-500">此文章已被設定為刪除！</span>
            @endif

            {{-- 文章標題 --}}
            <span class="text-xl font-semibold mt-2 md:mt-0">
                <a
                    href="@if ($post->trashed())
                        {{ route('posts.showSoftDeleted', [ 'id' => $post->id]) }}
                    @else
                        {{ $post->link_with_slug }}
                    @endif"
                    class="post-link hover:underline"
                >{{ $post->title }}</a>
            </span>

            {{-- 文章相關資訊 --}}
            <div class="flex items-center text-sm text-gray-400 mt-2 space-x-2">
                {{-- 文章分類資訊 --}}
                <div>
                    <a class="hover:text-gray-700"
                    href="{{ $post->category->link_with_name }}" title="{{ $post->category->name }}">
                        <i class="{{ $post->category->icon }}"></i><span class="ml-2">{{ $post->category->name }}</span>
                    </a>
                </div>
                <div>&bull;</div>
                {{-- 文章發布時間 --}}
                <div>
                    <a
                        href="@if ($post->trashed())
                            {{ route('posts.showSoftDeleted', [ 'id' => $post->id]) }}
                        @else
                            {{ $post->link_with_slug }}
                        @endif"
                        class="hover:text-gray-700"
                        title="文章發布於：{{ $post->created_at }}"
                    >
                        <i class="bi bi-clock-fill"></i><span class="ml-2">{{ $post->created_at->diffForHumans() }}</span>
                    </a>
                </div>
                <div>&bull;</div>
                <div>
                    {{-- 文章留言數 --}}
                    <a class="hover:text-gray-700"
                    href="{{ $post->link_with_slug }}#replies-card">
                        <i class="bi bi-chat-square-text-fill"></i><span class="ml-2">{{ $post->reply_count }}</span>
                    </a>
                </div>
            </div>
        </div>

        @if ($post->trashed())
            <div class="flex items-center mt-2 md:mt-0">
                <a
                    x-on:click.stop="return confirm('您確定恢復此文章嗎？');"
                    href="{{ route('posts.restorePost', [ 'id' => $post->id ]) }}"
                    class="flex justify-center items-center h-10 w-10 text-lg text-white font-bold bg-blue-600 hover:bg-blue-800 active:bg-blue-600 rounded-full
                    transform hover:-translate-x-1 transition duration-150 ease-in shadow-md hover:shadow-xl"
                >
                    <i class="bi bi-arrow-90deg-left"></i>
                </a>

                <form id="force-delete-post" action="{{ route('posts.forceDeletePost', ['id' => $post->id]) }}" method="POST"
                class="hidden">
                    @csrf
                    @method('DELETE')
                </form>

                {{-- Force Delete Button --}}
                <button
                    x-on:click.stop="return confirm('您確定要完全刪除此文章嗎？（此動作無法復原）');"
                    type="submit"
                    form="force-delete-post"
                    class="flex justify-center items-center h-10 w-10 text-lg text-white font-bold bg-purple-600 hover:bg-purple-800 active:bg-purple-600 rounded-full
                    transform hover:-translate-x-1 transition duration-150 ease-in shadow-md hover:shadow-xl ml-2"
                >
                    <i class="bi bi-exclamation-diamond-fill"></i>
                </button>
            </div>
        @else
            {{-- Edit Post --}}
            <div class="flex items-center mt-2 md:mt-0">
                <a
                    href="{{ route('posts.edit', ['post' => $post->id]) }}"
                    class="flex justify-center items-center h-10 w-10 text-lg text-white font-bold bg-green-600 hover:bg-green-800 active:bg-green-600 rounded-full
                    transform hover:-translate-x-1 transition duration-150 ease-in shadow-md hover:shadow-xl"
                >
                    <i class="bi bi-pencil"></i>
                </a>

                <form id="delete-post" action="{{ route('posts.destroy', ['post' => $post->id]) }}" method="POST"
                class="hidden">
                    @csrf
                    @method('DELETE')
                </form>

                {{-- Force Delete Button --}}
                <button
                    x-on:click.stop="return confirm('您確定要刪除此文章嗎？（時間內還可以恢復）');"
                    type="submit"
                    form="delete-post"
                    class="flex justify-center items-center h-10 w-10 text-lg text-white font-bold bg-red-600 hover:bg-red-800 active:bg-red-600 rounded-full
                    transform hover:-translate-x-1 transition duration-150 ease-in shadow-md hover:shadow-xl ml-2"
                >
                    <i class="bi bi-trash-fill"></i>
                </button>
            </div>
        @endif
    </div>

@empty
    <div class="posts-container transform hover:-translate-x-2 transition duration-150 ease-in shadow-md hover:shadow-xl bg-white rounded-xl
    flex justify-center items-center cursor-pointer ring-1 ring-black ring-opacity-20 w-full h-36">
        <span>目前沒有文章，該開始寫囉！</span>
    </div>
@endforelse

<div>
    {{ $posts->onEachSide(1)->withQueryString()->links() }}
</div>
