{{-- 回覆列表 --}}
<div class="card shadow mb-4">
    <div class="card-body p-4">
        <ul class="list-group list-group-flush">
            @foreach ($replies as $index => $reply)
                <li class="list-group-item" name="reply{{ $reply->id }}" id="reply{{ $reply->id }}">

                    <div class="row">
                        {{-- 作者大頭貼 --}}
                        <div class="col-2 d-flex justify-content-center align-items-center">
                            <a href="{{ route('users.show', [$reply->user_id]) }}">
                                <img class="rounded-circle"
                                alt="{{ $reply->user->name }}" src="{{ $reply->user->gravatar() }}""
                                width="48px" height="48px">
                            </a>
                        </div>

                        <div class="col-9 d-flex justify-content-start">
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

                        <div class="col-1 d-flex justify-content-center align-items-center">
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
                        </div>
                    </div>

                </li>
            @endforeach
        </ul>
    </div>
</div>
