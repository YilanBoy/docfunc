{{-- 文章列表區塊 --}}
<ul class="list-group list-group-flush">
    @foreach ($posts as $post)
        <li class="list-group-item d-flex justify-content-between align-items-center">

            <div class="d-flex flex-row">
                {{-- 作者大頭貼 --}}
                <div class="d-flex justify-content-between align-items-center me-4">
                    <a href="{{ route('users.show', ['user' => $post->user_id]) }}">
                        <img class="rounded-circle"
                        alt="{{ $post->user->name }}" src="{{ $post->user->gravatar() }}"
                        width="52px" height="52px">
                    </a>
                </div>

                {{-- 文章相關訊息 --}}
                <div class="d-flex flex-column">
                    <div class="p-1">
                        <span class="fs-5">
                            <a class="link-dark text-decoration-none" href="{{ $post->link_with_slug }}" title="{{ $post->title }}">
                                {{ $post->title }}
                            </a>
                        </span>
                    </div>

                    <div class="p-1">
                        {{-- 文章標籤--}}
                        @if ($post->tags->count() > 0)
                            <span class="text-primary"><i class="fas fa-tags"></i></span>

                            @foreach ($post->tags as $tag)
                                <a role="button" class="btn btn-primary btn-sm rounded-pill py-0 shadow" href="{{ route('tags.show', ['tag' => $tag->id]) }}">
                                    {{ $tag->name }}
                                </a>
                            @endforeach
                        @endif
                    </div>

                    <div class="small p-1">

                        {{-- 文章分類資訊 --}}
                        <a class="link-secondary text-decoration-none"
                        href="{{ $post->category->link_with_name }}" title="{{ $post->category->name }}">
                            <i class="far fa-folder"></i> {{ $post->category->name }}
                        </a>

                        <span> • </span>

                        {{-- 文章作者資訊 --}}
                        <a class="link-secondary text-decoration-none"
                        href="{{ route('users.show', ['user' => $post->user_id]) }}"
                        title="{{ $post->user->name }}">
                            <i class="far fa-user"></i> {{ $post->user->name }}
                        </a>

                        <span> • </span>

                        {{-- 文章發佈時間 --}}
                        <a class="link-secondary text-decoration-none"
                        href="{{ $post->link_with_slug }}"
                        title="文章發佈於：{{ $post->created_at }}">
                            <i class="far fa-clock"></i> {{ $post->created_at->diffForHumans() }}
                        </a>

                    </div>

                </div>
            </div>

            <span class="badge bg-secondary rounded-pill"> {{ $post->reply_count }} </span>
        </li>
    @endforeach
</ul>
