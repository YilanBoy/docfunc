{{-- 文章列表側邊欄區塊 --}}
<div class="card shadow mb-4 px-2 py-2">
    <div class="card-body">
        <div class="text-center text-dark"><strong>歡迎來到 {{ config('app.name') }} !</strong></div>
        <hr>
        <p>用部落格來紀錄自己生活上大大小小的事情吧！此部落格使用 Laravel 與 Bootstrap 編寫而成。</p>
        <a href="{{ route('posts.create') }}" class="btn btn-success w-100">
            <i class="fas fa-pen-nib"></i> 新增文章
        </a>
    </div>
</div>

@if ($popularTags->count())
    <div class="card shadow mb-4 px-2 py-2">
        <div class="card-body">
            <div class=" text-center text-dark"><strong>熱門標籤</strong></div>
            <hr>
            @foreach ($popularTags as $popularTag)
                <a role="button" class="btn btn-primary btn-sm rounded-pill py-0 mb-1" href="{{ route('tags.show', $popularTag->id) }}">
                    {{ $popularTag->name }}
                </a>
            @endforeach
        </div>
    </div>
@endif

@if ($links->count())
    <div class="card shadow mb-4 px-2 py-2">
        <div class="card-body">
            <div class="text-center text-dark"><strong>學習資源推薦</strong></div>
            <hr>
            @foreach ($links as $link)
                <a class="link-secondary text-decoration-none" href="{{ $link->link }}" target="_blank" rel="nofollow noopener noreferrer">
                    {{ $link->title }}
                </a>
                <br>
            @endforeach
        </div>
    </div>
@endif
