{{-- 會員發布文章區塊 --}}
@if (count($posts))

    <ul class="list-group mt-4 border-0">
        @foreach ($posts as $post)
            <li class="list-group-item pl-2 pr-2 border-right-0 border-left-0 @if($loop->first) border-top-0 @endif">
                <a href="{{ $post->linkWithSlug() }}">
                    {{ $post->title }}
                </a>
                <span class="meta float-right text-secondary">
                    {{ $post->reply_count }} 回覆
                    <span> ⋅ </span>
                    {{ $post->created_at->diffForHumans() }}
                </span>
            </li>
        @endforeach
    </ul>

@else
    <div class="empty-block">目前沒有文章，該開始寫囉！</div>
@endif

{{-- 分頁 --}}
<div class="mt-4 pt-1">
    {{ $posts->onEachSide(1)->links() }}
</div>
