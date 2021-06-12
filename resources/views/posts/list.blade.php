{{-- 文章列表區塊 --}}
<ul class="list-group list-group-flush">
    @foreach ($posts as $post)
        <li class="list-group-item">

            <div class="row">
                {{-- 作者大頭貼 --}}
                <div class="col-1 d-flex justify-content-center align-items-center">
                    <a href="{{ route('users.show', ['user' => $post->user_id]) }}">
                        <img class="rounded-circle"
                        alt="{{ $post->user->name }}" src="{{ $post->user->gravatar() }}"
                        width="52px" height="52px">
                    </a>
                </div>

                {{-- 文章相關訊息 --}}
                <div class="col-10">
                    <div class="p-1">
                        <span class="fs-5 fw-bold">
                            <a class="link-dark text-decoration-none" href="{{ $post->link_with_slug }}" title="{{ $post->title }}">
                                {{ $post->title }}
                            </a>
                        </span>
                    </div>

                    <div class="p-1 small">
                        <span class="text-secondary">
                            {{ $post->excerpt }}
                        </span>
                    </div>

                    <div class="p-1 small">
                        {{-- 文章分類資訊 --}}
                        <a class="link-secondary text-decoration-none"
                        href="{{ $post->category->link_with_name }}" title="{{ $post->category->name }}">
                            <i class="{{ $post->category->icon }}"></i> {{ $post->category->name }}
                        </a>

                        <span class="text-secondary mx-1">&bull;</span>

                        {{-- 文章作者資訊 --}}
                        <a class="link-secondary text-decoration-none"
                        href="{{ route('users.show', ['user' => $post->user_id]) }}"
                        title="{{ $post->user->name }}">
                            <i class="fas fa-user"></i> {{ $post->user->name }}
                        </a>

                        <span class="text-secondary mx-1">&bull;</span>

                        {{-- 文章發佈時間 --}}
                        <a class="link-secondary text-decoration-none"
                        href="{{ $post->link_with_slug }}"
                        title="文章發佈於：{{ $post->created_at }}">
                            <i class="fas fa-clock"></i> {{ $post->created_at->diffForHumans() }}
                        </a>

                    </div>

                </div>

                <div class="col-1 d-flex justify-content-end align-items-center">
                    <span class="text-secondary">
                        <i class="fas fa-comment"></i>
                        {{ $post->reply_count }}
                    </span>
                </div>
            </div>
        </li>
    @endforeach
</ul>
