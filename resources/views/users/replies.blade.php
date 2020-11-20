{{-- 會員文章回覆區塊 --}}
@if ($replies->count())

    <ul class="list-group mt-4 border-0">
        @foreach ($replies as $reply)
            <li class="list-group-item pl-2 pr-2 border-right-0 border-left-0 @if($loop->first) border-top-0 @endif">
                <a href="{{ $reply->post->linkWithSlug(['#reply' . $reply->id]) }}">
                    {{ $reply->post->title }}
                </a>

                <div class="reply-content text-secondary mt-2 mb-2">
                    {!! $reply->content !!}
                </div>

                <div class="text-secondary" style="font-size:0.9em;">
                    <i class="far fa-clock"></i> 回覆於 {{ $reply->created_at->diffForHumans() }}
                </div>
            </li>
        @endforeach
    </ul>

    {{-- 分頁 --}}
    <div class="d-flex justify-content-center mt-4 pt-1">
        {{ $replies->onEachSide(1)->withQueryString()->links() }}
    </div>
@else
    <div class="d-flex justify-content-center p-5">目前沒有回覆，快點找文章進行回覆吧！</div>
@endif
