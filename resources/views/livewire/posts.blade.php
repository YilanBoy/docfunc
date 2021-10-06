<div class="w-full mr-0 space-y-6 xl:w-2/3 xl:mr-6">

    {{-- 文章排序 --}}
    <div class="flex flex-col-reverse w-full md:flex-row md:justify-between">
        <nav class="flex w-full p-1 space-x-1 md:w-1/2 lg:w-2/5 rounded-xl bg-gray-400/30 dark:bg-white/30 dark:text-gray-50">
            <a
                wire:click.prevent="orderChange('latest')"
                href="{{ $currentUrl . '?order=latest' }}"
                @class([
                    'w-1/3 flex justify-center py-2 rounded-lg transition duration-300',
                    'bg-gray-50 dark:bg-gray-600' => ($order === 'latest'),
                    'hover:bg-gray-50 dark:hover:bg-gray-600' => ($order !== 'latest'),
                ])
            >最新文章</a>
            <a
                wire:click.prevent="orderChange('recent')"
                href="{{ $currentUrl . '?order=recent' }}"
                @class([
                    'w-1/3 flex justify-center px-4 py-2 rounded-lg transition duration-300',
                    'bg-gray-50 dark:bg-gray-600' => ($order === 'recent'),
                    'hover:bg-gray-50 dark:hover:bg-gray-600' => ($order !== 'recent'),
                ])
            >最近更新</a>
            <a
                wire:click.prevent="orderChange('comment')"
                href="{{ $currentUrl . '?order=comment' }}"
                @class([
                    'w-1/3 flex justify-center px-4 py-2 rounded-lg transition duration-300',
                    'bg-gray-50 dark:bg-gray-600' => ($order === 'comment'),
                    'hover:bg-gray-50 dark:hover:bg-gray-600' => ($order !== 'comment'),
                ])
            >最多留言</a>
        </nav>

        {{-- 文章分類訊息-桌面裝置 --}}
        @if (isset($category))
            <div class="flex items-center justify-center px-4 py-2 mb-2 text-blue-700 border border-blue-700 rounded-xl bg-gradient-to-br from-blue-100 to-blue-300 md:mb-0">
                <span class="font-bold">{{ $category->name }}：</span>
                <span>{{ $category->description }}</span>
            </div>
        @endif

        {{-- 文章標籤訊息-桌面裝置 --}}
        @if (isset($tag))
            <div class="flex items-center justify-center px-4 py-2 mb-2 text-gray-700 border border-gray-700 rounded-xl bg-gradient-to-br from-gray-100 to-gray-300 md:mb-0">
                <span>標籤：</span>
                <span class="font-bold">{{ $tag->name }}</span>
            </div>
        @endif
    </div>

    {{-- 文章列表 --}}
    @forelse ($posts as $post)
        <x-card
            x-data="cardLink"
            x-on:click="directToCardLink($event, $refs)"
            class="flex flex-col justify-between transition duration-150 ease-in transform cursor-pointer md:flex-row hover:shadow-xl hover:-translate-x-1"
        >
            {{-- 大頭貼 --}}
            <div class="flex-none">
                <a href="{{ route('users.show', ['user' => $post->user_id]) }}">
                    <img src="{{ $post->user->gravatar() }}" alt="avatar"
                    class="w-14 h-14 rounded-xl hover:ring-4 hover:ring-blue-400">
                </a>
            </div>

            {{-- 文章 --}}
            <div class="flex flex-col justify-between w-full md:mx-4">
                {{-- 文章標題 --}}
                <h1 class="mt-2 text-xl font-semibold md:mt-0 dark:text-gray-50">
                    <a
                        x-ref="cardLinkUrl"
                        href="{{ $post->link_with_slug }}"
                        class="fancy-link"
                    >{{ $post->title }}</a>
                </h1>

                {{-- 文章大綱 --}}
                <div class="mt-2 text-gray-400">
                    {{ $post->excerpt }}
                </div>

                {{-- 文章標籤 --}}
                @if ($post->tags_count > 0)
                    <div class="flex flex-wrap items-center mt-2">
                        <span class="mr-1 text-gray-400"><i class="bi bi-tags-fill"></i></span>

                        @foreach ($post->tags as $tag)
                            <x-tag :href="route('tags.show', ['tag' => $tag->id])">
                                {{ $tag->name }}
                            </x-tag>
                        @endforeach
                    </div>
                @endif

                {{-- 文章相關資訊 --}}
                <div class="flex items-center mt-2 space-x-2 text-sm text-gray-400">
                    {{-- 文章分類資訊 --}}
                    <div>
                        <a class="hover:text-gray-700 dark:hover:text-gray-50"
                        href="{{ $post->category->link_with_name }}" title="{{ $post->category->name }}">
                            <i class="{{ $post->category->icon }}"></i><span class="hidden ml-2 md:inline">{{ $post->category->name }}</span>
                        </a>
                    </div>
                    <div>&bull;</div>
                    {{-- 文章作者資訊 --}}
                    <div>
                        <a class="hover:text-gray-700 dark:hover:text-gray-50"
                        href="{{ route('users.show', ['user' => $post->user_id]) }}"
                        title="{{ $post->user->name }}">
                            <i class="bi bi-person-fill"></i><span class="hidden ml-2 md:inline">{{ $post->user->name }}</span>
                        </a>
                    </div>
                    <div>&bull;</div>
                    {{-- 文章發布時間 --}}
                    <div>
                        <a class="hover:text-gray-700 dark:hover:text-gray-50"
                        href="{{ $post->link_with_slug }}"
                        title="文章發布於：{{ $post->created_at }}">
                            <i class="bi bi-clock-fill"></i><span class="hidden ml-2 md:inline">{{ $post->created_at->diffForHumans() }}</span>
                        </a>
                    </div>
                    <div>&bull;</div>
                    <div>
                        {{-- 文章留言數 --}}
                        <a class="hover:text-gray-700 dark:hover:text-gray-50"
                        href="{{ $post->link_with_slug }}#comments">
                            <i class="bi bi-chat-square-text-fill"></i><span class="hidden ml-2 md:inline">{{ $post->comment_count }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </x-card>

    @empty
        <x-card class="flex items-center justify-center w-full transition duration-150 ease-in transform h-36 hover:-translate-x-2 hover:shadow-xl dark:text-gray-50">
            <span>Whoops！此分類底下還沒有文章，趕緊寫一篇吧！</span>
        </x-card>
    @endforelse

    <div>
        {{ $posts->onEachSide(1)->withQueryString()->links() }}
    </div>
</div>
