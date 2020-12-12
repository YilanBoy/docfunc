{{-- 會員文章回覆區塊 --}}
@if ($replies->count())

    <ul class="list-group list-group-flush mt-4">
        @foreach ($replies as $reply)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                {{-- 回覆 --}}
                <div class="d-flex flex-column">
                    <div class="p-1">
                        <a class="text-decoration-none"
                        href="{{ $reply->post->linkWithSlug(['#reply' . $reply->id]) }}">
                            {{ $reply->post->title }}
                        </a>
                    </div>

                    <div class="p-1">
                        <p class="card-text">
                            {!! $reply->content !!}
                        </p>
                    </div>
                </div>

                {{-- 回覆時間 --}}
                <div class="text-secondary">
                    <i class="far fa-clock"></i> 回覆於 {{ $reply->created_at->diffForHumans() }}
                </div>
            </li>
        @endforeach
    </ul>

    {{-- 分頁 --}}
    <div class="d-flex justify-content-center mt-4">
        {{ $replies->onEachSide(1)->withQueryString()->links() }}
    </div>
@else
    <div class="d-flex justify-content-center p-5">目前沒有回覆，快點找文章進行回覆吧！</div>
@endif
