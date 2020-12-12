{{-- 通知列表區塊 --}}
<li class="list-group-item px-0">
    <div class="d-flex">
        {{-- 大頭貼 --}}
        <div class="flex-fill w-15 d-flex justify-content-center align-items-center me-1">
            <a href="{{ route('users.show', $notification->data['user_id']) }}">
                <img class="rounded-circle"
                alt="{{ $notification->data['user_name'] }}"
                src="{{ $notification->data['user_avatar'] }}"
                width="48px" height="48px">
            </a>
        </div>

        {{-- 回覆內容 --}}
        <div class="flex-fill w-70 me-1">
            <div class="mb-1">
                <a class="text-decoration-none"
                href="{{ route('users.show', $notification->data['user_id']) }}">{{ $notification->data['user_name'] }}</a>
                回覆
                <a class="text-decoration-none"
                href="{{ $notification->data['post_link'] }}">{{ $notification->data['post_title'] }}</a>
            </div>
            <div class="">
                {!! $notification->data['reply_content'] !!}
            </div>
        </div>

        {{-- 回覆時間 --}}
        <div class="flex-fill w-15 d-flex justify-content-center align-items-center me-1">
            <span title="{{ $notification->created_at }}">
                <i class="far fa-clock"></i>
                {{ $notification->created_at->diffForHumans() }}
            </span>
        </div>
    </div>
</li>

