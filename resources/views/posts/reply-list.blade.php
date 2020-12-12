{{-- 回覆列表 --}}
<ul class="list-group list-group-flush mt-4">
    @foreach ($replies as $index => $reply)
        <li class="list-group-item d-flex justify-content-between align-items-center py-3"
        name="reply{{ $reply->id }}" id="reply{{ $reply->id }}">

            <div class="d-flex flex-row">
                {{-- 作者大頭貼 --}}
                <div class="d-flex justify-content-between align-items-center me-3">
                    <a href="{{ route('users.show', [$reply->user_id]) }}">
                        <img class="rounded-circle"
                        alt="{{ $reply->user->name }}" src="{{ $reply->user->gravatar() }}""
                        width="48px" height="48px">
                    </a>
                </div>

                {{-- 回覆內容 --}}
                <div class="d-flex flex-column">
                    <div class="p-1">
                        <a class="link-secondary text-decoration-none"
                        href="{{ route('users.show', [$reply->user_id]) }}" title="{{ $reply->user->name }}">
                            {{ $reply->user->name }}
                        </a>
                        <span class="text-secondary"> • </span>
                        <span class="text-secondary" title="{{ $reply->created_at }}">{{ $reply->created_at->diffForHumans() }}</span>
                    </div>

                    <div class="card-text p-1">
                        {!! $reply->content !!}
                    </div>
                </div>
            </div>

            {{-- 回覆刪除按鈕 --}}
            @can('destroy', $reply)
                <form action="{{ route('replies.destroy', $reply->id) }}"
                onsubmit="return confirm('確定刪除此評論？');"
                method="post">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="far fa-trash-alt"></i>
                    </button>
                </form>
            @endcan
        </li>
    @endforeach
</ul>
