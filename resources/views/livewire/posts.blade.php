<div class="w-full xl:w-2/3 mr-0 xl:mr-6 space-y-6">

    <div class="flex justify-between">
        {{-- Post Sort --}}
        <nav class="flex font-semibold">
            <a
                wire:click.prevent="setOrder('latest')"
                href="{{ $currentUrl . '?order=latest' }}"
                class="block transition duration-150 ease-in hover:border-blue-500 hover:text-gray-700
                border-b-4 px-2 sm:px-7 py-2
                @if ($order === 'latest') border-blue-500 text-gray-700 @else text-gray-400 @endif"
            >
                <span>最新文章</span>
            </a>

            <a
                wire:click.prevent="setOrder('recent')"
                href="{{ $currentUrl . '?order=recent' }}"
                class="block transition duration-150 ease-in hover:border-blue-500 hover:text-gray-700
                border-b-4 px-2 sm:px-7 py-2
                @if ($order === 'recent') border-blue-500 text-gray-700 @else text-gray-400 @endif"
            >
                <span>最近更新</span>
            </a>

            <a
                wire:click.prevent="setOrder('reply')"
                href="{{ $currentUrl . '?order=reply' }}"
                class="block transition duration-150 ease-in hover:border-blue-500 hover:text-gray-700
                border-b-4 px-2 sm:px-7 py-2
                @if ($order === 'reply') border-blue-500 text-gray-700 @else text-gray-400 @endif"
            >
                <span>最多留言</span>
            </a>
        </nav>

        {{-- 分類訊息區塊 --}}
        @if (isset($category))
            <div class="hidden md:flex justify-center items-center text-blue-700 border-blue-700 border-2 rounded-xl
            bg-gradient-to-br from-blue-100 to-blue-300 px-4 py-2">
                <span class="font-bold">{{ $category->name }}：</span>
                <span>{{ $category->description }}</span>
            </div>
        @endif

        {{-- 標籤訊息區塊 --}}
        @if (isset($tag))
            <div class="hidden md:flex justify-center items-center text-green-700 border-green-700 border-2 rounded-xl
            bg-gradient-to-br from-green-100 to-green-300 px-4 py-2">
                <span class="font-bold">{{ $tag->name }}</>
            </div>
        @endif
    </div>

    {{-- Posts --}}
    @forelse ($posts as $post)
        <div
            x-data
            x-on:click="
                const targetTagName = $event.target.tagName.toLowerCase()
                const ignores = ['a']

                if (!ignores.includes(targetTagName)) {
                    $refs.postLink.click()
                }
            "
            class="flex flex-col md:flex-row justify-between p-4 shadow-md hover:shadow-xl bg-white rounded-xl
            transform hover:-translate-x-2 transition duration-150 ease-in cursor-pointer ring-1 ring-black ring-opacity-20"
        >
            {{-- 大頭貼 --}}
            <div class="flex-none">
                <a href="{{ route('users.show', ['user' => $post->user_id]) }}">
                    <img src="{{ $post->user->gravatar() }}" alt="avatar"
                    class="w-14 h-14 rounded-xl hover:ring-4 hover:ring-blue-400">
                </a>
            </div>

            {{-- 文章 --}}
            <div class="w-full flex flex-col justify-between md:mx-4">
                {{-- 文章標題 --}}
                <h1 class="text-xl font-semibold mt-2 md:mt-0">
                    <a
                        x-ref="postLink"
                        href="{{ $post->link_with_slug }}"
                        class="hover:underline"
                    >{{ $post->title }}</a>
                </h1>

                {{-- 文章大綱 --}}
                <div class="text-gray-600 mt-2">
                    {{ $post->excerpt }}
                </div>

                {{-- 文章相關資訊 --}}
                <div class="flex items-center text-sm text-gray-400 mt-4 space-x-2">
                    {{-- 文章分類資訊 --}}
                    <div>
                        <a class="hover:text-gray-700"
                        href="{{ $post->category->link_with_name }}" title="{{ $post->category->name }}">
                            <i class="{{ $post->category->icon }}"></i><span class="hidden md:inline ml-2">{{ $post->category->name }}</span>
                        </a>
                    </div>
                    <div>&bull;</div>
                    {{-- 文章作者資訊 --}}
                    <div>
                        <a class="hover:text-gray-700"
                        href="{{ route('users.show', ['user' => $post->user_id]) }}"
                        title="{{ $post->user->name }}">
                            <i class="bi bi-person-fill"></i><span class="hidden md:inline ml-2">{{ $post->user->name }}</span>
                        </a>
                    </div>
                    <div>&bull;</div>
                    {{-- 文章發布時間 --}}
                    <div>
                        <a class="hover:text-gray-700"
                        href="{{ $post->link_with_slug }}"
                        title="文章發布於：{{ $post->created_at }}">
                            <i class="bi bi-clock-fill"></i><span class="hidden md:inline ml-2">{{ $post->created_at->diffForHumans() }}</span>
                        </a>
                    </div>
                    <div>&bull;</div>
                    <div>
                        {{-- 文章留言數 --}}
                        <a class="hover:text-gray-700"
                        href="{{ $post->link_with_slug }}#replies-card">
                            <i class="bi bi-chat-square-text-fill"></i><span class="hidden md:inline ml-2">{{ $post->reply_count }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

    @empty
        <div class="transform hover:-translate-x-2 transition duration-150 ease-in shadow-md hover:shadow-xl bg-white rounded-xl
        flex justify-center items-center cursor-pointer ring-1 ring-black ring-opacity-20 w-full h-36">
            <span>Whoops！此分類底下還沒有文章，趕緊寫一篇吧！</span>
        </div>
    @endforelse

    <div>
        {{ $posts->onEachSide(1)->withQueryString()->links() }}
    </div>
</div>
