{{-- 會員發布文章區塊 --}}
@if ($posts->count())
    <ul class="list-group list-group-flush mt-4">
        @foreach ($posts as $post)
            <li class="list-group-item py-3">

                <div class="row">

                    <div class="col-9">
                        {{-- 文章 --}}
                        <a class="text-decoration-none"
                        href="{{ $post->linkWithSlug() }}">
                            {{ $post->title }}
                        </a>
                    </div>

                    <div class="col-3 d-flex justify-content-end align-items-center">
                        {{-- 文章發佈時間 --}}
                        <span class="text-secondary">
                            {{ $post->reply_count }} 回覆
                            <span> • </span>
                            {{ $post->created_at->diffForHumans() }}
                        </span>
                    </div>

                </div>

            </li>
        @endforeach
    </ul>

    {{-- 分頁 --}}
    <div class="d-flex justify-content-center mt-4">
        {{ $posts->onEachSide(1)->links() }}
    </div>
@else
    <div class="d-flex justify-content-center p-5">目前沒有文章，該開始寫囉！</div>
@endif
