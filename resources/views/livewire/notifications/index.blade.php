@section('title', '我的通知')

{{-- 通知列表 --}}
<div class="container flex-1 mx-auto max-w-7xl">
  <div class="flex items-start justify-center px-4 xl:px-0">

    <div class="flex flex-col items-center justify-center w-full space-y-6 md:w-2/3 xl:w-1/2">
      {{-- 頁面標題 --}}
      <div class="text-2xl text-gray-700 fill-current dark:text-gray-50">
        <i class="bi bi-bell-fill"></i><span class="ml-4">我的通知</span>
      </div>

      {{-- 通知列表 --}}
      @forelse ($notifications as $notification)
        <x-card
          x-data="cardLink"
          x-on:click="directToCardLink($event, $refs)"
          class="flex flex-col justify-between w-full cursor-pointer md:flex-row"
        >
          {{-- 大頭貼 --}}
          <div class="flex-none">
            <a href="{{ route('users.index', ['user' => $notification->data['user_id']]) }}">
              <img
                alt="{{ $notification->data['user_name'] }}"
                src="{{ $notification->data['user_avatar'] }}"
                class="w-14 h-14 rounded-xl hover:ring-4 hover:ring-blue-400"
              >
            </a>
          </div>

          {{-- 通知內容 --}}
          <div class="flex flex-col justify-between w-full md:mx-4">
            {{-- 文章標題 --}}
            <div class="mt-2 md:mt-0">
              <a
                href="{{ route('users.index', ['user' => $notification->data['user_id']]) }}"
                class="text-gray-400 hover:text-gray-700 dark:hover:text-gray-50"
              >{{ $notification->data['user_name'] }}</a>
              <span class="dark:text-gray-50">留言了你的文章</span>
              <a
                x-ref="cardLinkUrl"
                class="text-gray-400 hover:text-gray-700 dark:hover:text-gray-50"
                href="{{ $notification->data['post_link'] }}"
              >
                {{ '「' . $notification->data['post_title'] . '」' }}
              </a>
            </div>

            <div class="mt-2 text-gray-600 dark:text-gray-50">
              {!! $notification->data['comment_content'] !!}
            </div>

            {{-- 通知時間 --}}
            <div class="mt-4 text-sm text-gray-400">
              <i class="bi bi-clock-fill"></i>
              <span class="ml-2" title="{{ $notification->created_at }}">
                  {{ $notification->created_at->diffForHumans() }}
                </span>
            </div>
          </div>

        </x-card>

      @empty
        <x-card class="flex items-center justify-center w-full h-24 dark:text-gray-50">
          <span>沒有消息通知！</span>
        </x-card>
      @endforelse

      <div>
        {{ $notifications->links() }}
      </div>
    </div>

  </div>
</div>
