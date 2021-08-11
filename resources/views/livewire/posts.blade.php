<div class="w-full xl:w-2/3 mr-0 xl:mr-6 space-y-6">

    <div class="flex justify-between">
        {{-- Post Sort --}}
        <nav class="flex font-semibold">
            <div class="group">
                <a
                    wire:click.prevent="orderChange('latest')"
                    href="{{ $currentUrl . '?order=latest' }}"
                    @class([
                        'block transition duration-300 ease-in px-2 sm:px-7 py-2',
                        'text-gray-700 dark:text-white' => ($order === 'latest'),
                        'text-gray-400 hover:text-gray-700 dark:hover:text-white' => ($order !== 'latest'),
                    ])
                >
                    <span>最新文章</span>
                </a>
                <div class="bg-gray-200 dark:bg-gray-600">
                    <div
                        @class([
                            'h-1 bg-blue-500 transition-all duration-300',
                            'w-full' => ($order === 'latest'),
                            'w-0 group-hover:w-full' => ($order !== 'latest'),
                        ])
                    ></div>
                </div>
            </div>

            <div class="group">
                <a
                    wire:click.prevent="orderChange('recent')"
                    href="{{ $currentUrl . '?order=recent' }}"
                    @class([
                        'block transition duration-300 ease-in px-2 sm:px-7 py-2',
                        'text-gray-700 dark:text-white' => ($order === 'recent'),
                        'text-gray-400 hover:text-gray-700 dark:hover:text-white' => ($order !== 'recent'),
                    ])
                >
                    <span>最近更新</span>
                </a>
                <div class="bg-gray-200 dark:bg-gray-600">
                    <div
                        @class([
                            'h-1 bg-blue-500 transition-all duration-300',
                            'w-full' => ($order === 'recent'),
                            'w-0 group-hover:w-full' => ($order !== 'recent'),
                        ])
                    ></div>
                </div>
            </div>

            <div class="group">
                <a
                    wire:click.prevent="orderChange('reply')"
                    href="{{ $currentUrl . '?order=reply' }}"
                    @class([
                        'block transition duration-300 ease-in px-2 sm:px-7 py-2',
                        'text-gray-700 dark:text-white' => ($order === 'reply'),
                        'text-gray-400 hover:text-gray-700 dark:hover:text-white' => ($order !== 'reply'),
                    ])
                >
                    <span>最多留言</span>
                </a>
                <div class="bg-gray-200 dark:bg-gray-600">
                    <div
                        @class([
                            'h-1 bg-blue-500 transition-all duration-300',
                            'w-full' => ($order === 'reply'),
                            'w-0 group-hover:w-full' => ($order !== 'reply'),
                        ])
                    ></div>
                </div>
            </div>
        </nav>

        {{-- 分類訊息區塊 --}}
        @if (isset($category))
            <div class="hidden md:flex justify-center items-center text-blue-700 border-blue-700 border rounded-xl
            bg-gradient-to-br from-blue-100 to-blue-300 px-4 py-2">
                <span class="font-bold">{{ $category->name }}：</span>
                <span>{{ $category->description }}</span>
            </div>
        @endif

        {{-- 標籤訊息區塊 --}}
        @if (isset($tag))
            <div class="hidden md:flex justify-center items-center text-green-700 border-green-700 border rounded-xl
            bg-gradient-to-br from-green-100 to-green-300 px-4 py-2">
                <span class="font-bold">{{ $tag->name }}</span>
            </div>
        @endif
    </div>

    {{-- Posts --}}
    @forelse ($posts as $post)
        <x-card
            x-data="{}"
            x-on:click="
                const targetTagName = $event.target.tagName.toLowerCase()
                const ignores = ['a']

                if (!ignores.includes(targetTagName)) {
                    $refs.postLink.click()
                }
            "
            class="flex flex-col md:flex-row justify-between hover:shadow-xl
            transform hover:-translate-x-2 transition duration-150 ease-in cursor-pointer"
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
                <h1 class="text-xl font-semibold mt-2 md:mt-0 dark:text-white">
                    <a
                        x-ref="postLink"
                        href="{{ $post->link_with_slug }}"
                        class="hover:underline"
                    >{{ $post->title }}</a>
                </h1>

                {{-- 文章大綱 --}}
                <div class="text-gray-600 mt-2 dark:text-white">
                    {{ $post->excerpt }}
                </div>

                {{-- 文章相關資訊 --}}
                <div class="flex items-center text-sm text-gray-400 mt-4 space-x-2">
                    {{-- 文章分類資訊 --}}
                    <div>
                        <a class="hover:text-gray-700 dark:hover:text-white"
                        href="{{ $post->category->link_with_name }}" title="{{ $post->category->name }}">
                            <i class="{{ $post->category->icon }}"></i><span class="hidden md:inline ml-2">{{ $post->category->name }}</span>
                        </a>
                    </div>
                    <div>&bull;</div>
                    {{-- 文章作者資訊 --}}
                    <div>
                        <a class="hover:text-gray-700 dark:hover:text-white"
                        href="{{ route('users.show', ['user' => $post->user_id]) }}"
                        title="{{ $post->user->name }}">
                            <i class="bi bi-person-fill"></i><span class="hidden md:inline ml-2">{{ $post->user->name }}</span>
                        </a>
                    </div>
                    <div>&bull;</div>
                    {{-- 文章發布時間 --}}
                    <div>
                        <a class="hover:text-gray-700 dark:hover:text-white"
                        href="{{ $post->link_with_slug }}"
                        title="文章發布於：{{ $post->created_at }}">
                            <i class="bi bi-clock-fill"></i><span class="hidden md:inline ml-2">{{ $post->created_at->diffForHumans() }}</span>
                        </a>
                    </div>
                    <div>&bull;</div>
                    <div>
                        {{-- 文章留言數 --}}
                        <a class="hover:text-gray-700 dark:hover:text-white"
                        href="{{ $post->link_with_slug }}#post-{{ $post->id }}-replies">
                            <i class="bi bi-chat-square-text-fill"></i><span class="hidden md:inline ml-2">{{ $post->reply_count }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </x-card>

    @empty
        <x-card class="w-full h-36 flex justify-center items-center
        transform hover:-translate-x-2 transition duration-150 ease-in hover:shadow-xl
        dark:text-white">
            <span>Whoops！此分類底下還沒有文章，趕緊寫一篇吧！</span>
        </x-card>
    @endforelse

    <div>
        {{ $posts->onEachSide(1)->withQueryString()->links() }}
    </div>
</div>
