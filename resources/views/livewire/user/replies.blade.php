{{-- 會員留言 --}}
<div class="space-y-6">

    <div>
        {{ $replies->onEachSide(1)->withQueryString()->links() }}
    </div>

    @forelse ($replies as $reply)
        <x-card
            x-data="cardLink"
            x-on:click="replyCardLink($event, $refs)"
            class="flex flex-col md:flex-row justify-between hover:shadow-xl
            transform hover:-translate-x-2 transition duration-150 ease-in cursor-pointer"
        >
            {{-- 留言相關資訊 --}}
            <div class="w-full flex justify-between">
                {{-- 文章標題 --}}
                <div class="flex flex-col justify-between">
                    <span class="text-xl font-semibold dark:text-white">
                        <a
                            x-ref="replyLink"
                            href="{{ $reply->post->link_with_slug }}#post-{{ $reply->post->id }}-reply-{{ $reply->id }}"
                            class="fancy-link"
                        >
                            {{ $reply->post->title }}
                        </a>
                    </span>

                    <span class="mt-2 text-gray-600 dark:text-white">
                        {!! nl2br(e($reply->content)) !!}
                    </span>

                    <span class="xl:hidden mt-2 text-gray-400">
                        <i class="bi bi-clock-fill"></i><span class="ml-2">{{ $reply->created_at->diffForHumans() }}</span>
                    </span>
                </div>

                {{-- 文章發布時間 --}}
                <span class="hidden xl:flex text-gray-400 justify-center items-center">
                    <i class="bi bi-clock-fill"></i><span class="ml-2">{{ $reply->created_at->diffForHumans() }}</span>
                </span>

            </div>
        </x-card>

    @empty
        <x-card class="w-full h-36 flex justify-center items-center
        transform hover:-translate-x-2 transition duration-150 ease-in hover:shadow-xl
        dark:text-white">
            <span>目前沒有留言，快點找文章進行留言吧！</span>
        </x-card>
    @endforelse
</div>
