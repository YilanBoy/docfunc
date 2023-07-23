<x-dashed-card>
  <div class="flex flex-col">
    {{-- 大頭貼 --}}
    <div class="flex items-center space-x-4 text-base">
      @if ($userId !== 0)
        <a href="{{ route('users.index', ['user' => $userId]) }}">
          <img
            class="h-8 w-8 rounded-full hover:ring-2 hover:ring-blue-400"
            src="{{ $userGravatarUrl }}"
            alt="{{ $userName }}"
          >
        </a>

        <span class="font-semibold dark:text-gray-50">{{ $userName }}</span>
      @else
        <span class="font-semibold dark:text-gray-50">訪客</span>
      @endif

      <span class="text-gray-400">{{ $createdAt }}</span>
      @if ($isEdited)
        <span class="text-gray-400">(已編輯)</span>
      @endif
    </div>

    {{-- 留言 --}}
    <div class="comment-body">
      {!! $this->convertedBody !!}
    </div>

    <div class="flex items-center justify-between">
      <div class="flex items-center space-x-6 text-base text-gray-400">
        @auth
          @if (auth()->id() === $userId)
            <button
              class="hover:text-gray-500 dark:hover:text-gray-300"
              type="button"
              wire:click="$emit('setEditComment', {{ $commentId }}, {{ $offset }})"
            >
              <i class="bi bi-pencil-fill"></i>
              <span class="ml-1">編輯</span>
            </button>
          @endif

          @if (in_array(auth()->id(), [$userId, $postUserId]))
            <button
              class="hover:text-gray-500 dark:hover:text-gray-300"
              type="button"
              onclick="confirm('你確定要刪除該留言？') || event.stopImmediatePropagation()"
              wire:click="destroy({{ $commentId }})"
            >
              <i class="bi bi-trash3-fill"></i>
              <span class="ml-1">刪除</span>
            </button>
          @endif
        @endauth

      </div>
    </div>
  </div>
</x-dashed-card>
