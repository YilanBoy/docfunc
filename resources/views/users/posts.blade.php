{{-- 會員發布文章區塊 --}}
@forelse ($posts as $post)
    <div
        x-data
        x-on:click="
            const clicked = $event.target
            const target = clicked.tagName.toLowerCase()
            const ignores = ['a']

            if (!ignores.includes(target)) {
                clicked.closest('.posts-container').querySelector('.post-link').click()
            }
        "
        class="posts-container flex flex-col md:flex-row justify-between p-4 shadow-md hover:shadow-xl bg-white rounded-xl
        transform hover:-translate-x-2 transition duration-150 ease-in cursor-pointer ring-1 ring-black ring-opacity-20"
    >
        {{-- 文章 --}}
        <div class="w-full flex flex-col justify-between">
            {{-- 文章標題 --}}
            <span class="text-xl font-semibold mt-2 md:mt-0">
                <a href="{{ $post->link_with_slug }}" class="post-link hover:underline">{{ $post->title }}</a>
            </span>

            {{-- flex items-center text-sm text-gray-400 mt-4 space-x-2 --}}

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
                    <a class="hover:text-gray-700"
                    href="{{ $post->link_with_slug }}"
                    title="文章發布於：{{ $post->created_at }}">
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
