<div class="w-full xl:w-2/3 mr-0 xl:mr-6">
    <div class="space-y-6 mb-6">
        {{-- 分類訊息區塊 --}}
        @if (isset($category))
            <div class="flex justify-center items-center text-lg bg-gradient-to-br from-blue-100 to-blue-300 text-blue-700 border-2 border-blue-700 p-4 rounded-xl">
                <span class="font-bold">{{ $category->name }}：</span>
                <span>{{ $category->description }}</span>
            </div>
        @endif

        {{-- 標籤訊息區塊 --}}
        @if (isset($tag))
            <div class="flex justify-center items-center text-lg bg-gradient-to-br from-green-100 to-green-300 text-green-700 border-2 border-green-700 p-4 rounded-xl">
                <span class="font-bold">{{ $tag->name }}</>
            </div>
        @endif

        {{-- Post Sort --}}
        <nav class="flex items-center">
            <ul class="flex uppercase font-semibold pb-2">
                <li>
                    <a
                        wire:click.prevent="setOrder('latest')"
                        href="{{ $currentUrl . '?order=latest' }}"
                        class="transition duration-150 ease-in border-b-4 px-2 sm:px-7 pb-3 hover:border-blue-500 hover:text-gray-700
                        @if ($order === 'latest') border-blue-500 text-gray-700 @else text-gray-400 @endif"
                    >
                        <span>最新文章</span>
                    </a>
                </li>
                <li>
                    <a
                        wire:click.prevent="setOrder('recent')"
                        href="{{ $currentUrl . '?order=recent' }}"
                        class="transition duration-150 ease-in border-b-4 px-2 sm:px-7 pb-3 hover:border-blue-500 hover:text-gray-700
                        @if ($order === 'recent') border-blue-500 text-gray-700 @else text-gray-400 @endif"
                    >
                        <span>最近更新</span>
                    </a>
                </li>
                <li>
                    <a
                        wire:click.prevent="setOrder('reply')"
                        href="{{ $currentUrl . '?order=reply' }}"
                        class=" transition duration-150 ease-in border-b-4 px-2 sm:px-7 pb-3 hover:border-blue-500 hover:text-gray-700
                        @if ($order === 'reply') border-blue-500 text-gray-700 @else text-gray-400 @endif"
                    >
                        <span>最多留言</span>
                    </a>
                </li>
            </ul>
        </nav>

        {{-- Post Cards --}}
        @forelse ($posts as $post)
            <div
                x-data
                x-on:click="
                    const clicked = $event.target
                    const target = clicked.tagName.toLowerCase()
                    const ignores = ['a']

                    if (!ignores.includes(target)) {
                        clicked.closest('.post-container').querySelector('.post-link').click()
                    }
                "
                class="post-container transform hover:-translate-x-2 transition duration-150 ease-in shadow-md
                hover:shadow-xl bg-white rounded-xl cursor-pointer ring-1 ring-black ring-opacity-20"
            >
                <div class="flex flex-col md:flex-row justify-between p-4">
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
                            <a href="{{ $post->link_with_slug }}" class="post-link hover:underline">{{ $post->title }}</a>
                        </h1>

                        {{-- 文章大綱 --}}
                        <div class="text-gray-600 mt-2">
                            {{ $post->excerpt }}
                        </div>

                        {{-- 文章相關資訊 --}}
                        <div class="flex flex-col md:flex-row md:items-center justify-between mt-4">
                            <div class="flex items-center text-sm text-gray-400 space-x-2">
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
                </div>
            </div>

        @empty
            <div
                class="post-container transform hover:-translate-x-2 transition duration-150 ease-in shadow-md hover:shadow-xl bg-white rounded-xl
                flex justify-center items-center cursor-pointer ring-1 ring-black ring-opacity-20 h-36"
            >
                <span>Whoops！此分類底下還沒有文章，趕緊寫一篇吧！</span>
            </div>
        @endforelse
    </div>

    <div>
        {{ $posts->onEachSide(1)->withQueryString()->links() }}
    </div>
</div>
