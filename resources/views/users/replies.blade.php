{{-- 會員文章回覆區塊 --}}
@forelse ($replies as $reply)
    <div
        x-data
        x-on:click="
            const clicked = $event.target
            const target = clicked.tagName.toLowerCase()
            const ignores = ['a']

            if (!ignores.includes(target)) {
                clicked.closest('.reply-container').querySelector('.reply-link').click()
            }
        "
        class="reply-container flex flex-col md:flex-row justify-between p-4 shadow-md hover:shadow-xl bg-white rounded-xl
        transform hover:-translate-x-2 transition duration-150 ease-in cursor-pointer ring-1 ring-black ring-opacity-20"
    >
        {{-- 回覆 --}}
        <div class="w-full flex justify-between">
            {{-- 文章標題 --}}
            <div class="flex flex-col justify-between">
                <span class="text-xl font-semibold">
                    <a href="{{ $reply->post->link_with_slug . '#reply-' . $reply->id }}" class="reply-link hover:underline">
                        {{ $reply->post->title }}
                    </a>
                </span>

                <span class="mt-2">
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
    </div>

@empty
    <div class="post-container transform hover:-translate-x-2 transition duration-150 ease-in shadow-md hover:shadow-xl bg-white rounded-xl
    flex justify-center items-center cursor-pointer ring-1 ring-black ring-opacity-20 w-full h-36">
        <span>目前沒有回覆，快點找文章進行回覆吧！</span>
    </div>
@endforelse

<div>
    {{ $replies->onEachSide(1)->withQueryString()->links() }}
</div>
