<x-dashed-card>
  <div class="flex flex-col">
    {{-- 大頭貼 --}}
    <div class="flex items-center space-x-4 text-base">
      @if ($userId !== 0)
        <a
          href="{{ route('users.show', ['user' => $userId]) }}"
          wire:navigate
        >
          <img
            class="size-10 rounded-full hover:ring-2 hover:ring-blue-400"
            src="{{ $userGravatarUrl }}"
            alt="{{ $userName }}"
          >
        </a>

        <span class="dark:text-gray-50">{{ $userName }}</span>
      @else
        <span class="dark:text-gray-50">訪客</span>
      @endif

      <span class="text-gray-400">{{ $createdAt->format('Y 年 m 月 d 日') }}</span>
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
              class="flex items-center hover:text-gray-500 dark:hover:text-gray-300"
              type="button"
              wire:click="$dispatch('set-edit-comment', { comment: {{ $commentId }} })"
            >
              <x-icon.pencil class="w-4" />
              <span class="ml-2">編輯</span>
            </button>
          @endif

          @if (in_array(auth()->id(), [$userId, $postAuthorId]))
            <button
              class="flex items-center hover:text-gray-500 dark:hover:text-gray-300"
              type="button"
              wire:confirm="你確定要刪除該留言？"
              wire:click="$parent.destroy({{ $commentId }})"
            >
              <x-icon.trash class="w-4" />
              <span class="ml-2">刪除</span>
            </button>
          @endif
        @endauth

      </div>
    </div>
  </div>
</x-dashed-card>
