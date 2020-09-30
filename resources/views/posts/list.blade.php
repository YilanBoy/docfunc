{{-- 文章列表區塊 --}}
@if (count($posts))
    <ul class="list-unstyled">
        @foreach ($posts as $post)
            <li class="media">

                {{-- 作者大頭貼 --}}
                <div class="media-left align-self-center">
                    <a href="{{ route('users.show', [$post->user_id]) }}">
                        <img class="media-object img-thumbnail mr-3" style="width: 52px; height: 52px;" src="{{ $post->user->gravatar() }}" title="{{ $post->user->name }}">
                    </a>
                </div>

                {{-- 文章相關訊息 --}}
                <div class="media-body">

                    <div class="media-heading mt-0 mb-1">
                        <h5 class="font-weight-bold">
                            <a href="{{ $post->linkWithSlug() }}" title="{{ $post->title }}">
                                {{ $post->title }}
                            </a>
                        </h5>
                        <a class="float-right" href="{{ $post->linkWithSlug() }}">
                            <span class="badge badge-secondary badge-pill"> {{ $post->reply_count }} </span>
                        </a>
                    </div>

                    <div class="media-body mb-1">
                        {{-- 文章標籤--}}
                        @if ($post->tags()->exists())
                            <i class="fas fa-tags text-info"></i>

                            @foreach ($post->tags as $tag)
                                <a class="badge badge-pill badge-lg bgi-gradient text-white" href="{{ route('tags.show', $tag->id) }}" title="{{ $tag->name }}">
                                    {{ $tag->name }}
                                </a>
                            @endforeach
                        @endif
                    </div>

                    <div class="media-body small text-secondary">

                        {{-- 文章分類資訊 --}}
                        <a class="text-secondary" href="{{ $post->category->linkWithName() }}" title="{{ $post->category->name }}">
                            <i class="far fa-folder"></i>
                            {{ $post->category->name }}
                        </a>

                        <span> • </span>

                        {{-- 文章作者資訊 --}}
                        <a class="text-secondary" href="{{ route('users.show', [$post->user_id]) }}" title="{{ $post->user->name }}">
                            <i class="far fa-user"></i>
                            {{ $post->user->name }}
                        </a>

                        <span> • </span>

                        {{-- 文章發佈時間 --}}
                        <a class="text-secondary" href="{{ $post->linkWithSlug() }}" title="文章發佈於：{{ $post->created_at }}">
                            <i class="far fa-clock"></i>
                            {{ $post->created_at->diffForHumans() }}
                        </a>

                    </div>

                </div>
            </li>

            @if (!$loop->last)
                <hr>
            @endif

        @endforeach
    </ul>
@else
    <div class="empty-block">目前此分類下沒有文章喔 ~_~ </div>
@endif
