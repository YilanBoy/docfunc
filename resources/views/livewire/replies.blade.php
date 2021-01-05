<div>
    {{-- 評論回覆 --}}
    @if (Auth::check())
        <div class="card shadow mb-4">
            <div class="card-body p-4">

                <div class="form-floating mb-3">
                    <textarea class="form-control" placeholder="content"
                    wire:model.debounce.500ms="content" id="floatingTextarea"
                    style="height: 100px"></textarea>
                    <label for="floatingTextarea">分享你的評論~</label>
                </div>

                <div class="d-flex justify-content-between">
                    <div class="d-flex justify-content-center align-items-center">
                        @error('content') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <button class="btn btn-primary" wire:click="store"><i class="fas fa-share"></i> 回覆</button>
                </div>
            </div>
        </div>
    @endif

    {{-- 回覆列表 --}}
    @if ($replies->count() > 0)
        <div class="card shadow mb-4">
            <div class="card-body p-4">
                <ul class="list-group list-group-flush">
                    @foreach ($replies as $reply)
                        <li class="list-group-item" name="reply-{{ $reply->id }}" id="reply-{{ $reply->id }}">

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
                                            {!! nl2br(e($reply->content)) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-1 d-flex justify-content-center align-items-center">
                                    {{-- 回覆刪除按鈕 --}}
                                    @can('destroy', $reply)
                                        <button class="btn btn-danger btn-sm"
                                        onclick="confirm('您確定要刪除此回覆嗎？') || event.stopImmediatePropagation()"
                                        wire:click="destroy({{ $reply->id }})">
                                            <i class="far fa-trash-alt"></i>
                                        </button>
                                    @endcan
                                </div>
                            </div>

                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif
</div>


