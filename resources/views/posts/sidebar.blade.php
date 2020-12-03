{{-- 文章列表側邊欄區塊 --}}
<div class="card shadow mb-4">
    <div class="card-body pt-2">
        <div class="text-center mt-1 mb-0 text-dark"><strong>歡迎來到 {{ config('app.name') }} !</strong></div>
        <hr class="mt-2 mb-3">
        <p>用部落格來紀錄自己生活上大大小小的事情吧！此部落格使用 Laravel 與 Bootstrap 編寫而成。</p>
        <a href="{{ route('posts.create') }}" class="btn btn-success btn-block" aria-label="Left Align">
            <i class="fas fa-pen-nib mr-2"></i>新增文章
        </a>
    </div>
</div>

@if ($popularTags->count())
    <div class="card shadow mb-4">
        <div class="card-body pt-2">
            <div class="text-center mt-1 mb-0 text-dark"><strong>熱門標籤</strong></div>
            <hr class="mt-2 mb-3">
            @foreach ($popularTags as $popularTag)
                <a class="badge badge-pill badge-primary badge-lg" href="{{ route('tags.show', $popularTag->id) }}" title="{{ $popularTag->name }}">
                    {{ $popularTag->name }}
                </a>
            @endforeach
        </div>
    </div>
@endif

@if ($links->count())
    <div class="card shadow mb-4">
        <div class="card-body pt-2">
            <div class="text-center mt-1 mb-0 text-dark"><strong>學習資源推薦</strong></div>
            <hr class="mt-2 mb-3">
            @foreach ($links as $link)
                <a class="media mt-1" href="{{ $link->link }}" target="_blank" rel="nofollow noopener noreferrer">
                    <div class="media-body">
                        <span class="media-heading text-muted">{{ $link->title }}</span>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
@endif
