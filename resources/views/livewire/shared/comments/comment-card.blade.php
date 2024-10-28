@script
  <script>
    Alpine.data('commentCard', () => ({
      setEditComment() {
        this.$dispatch('open-edit-comment-modal', {
          commentId: this.$root.dataset.commentId,
          body: this.$root.dataset.body
        })
      },
      openCreateCommentModal() {
        this.$dispatch('open-create-comment-modal', {
          parentId: this.$root.dataset.commentId
        })
      }
    }));
  </script>
@endscript

<div
  data-comment-id="{{ $commentId }}"
  data-body="{{ $body }}"
  x-data="commentCard"
>
  <x-dashed-card class="mt-6">
    <div class="flex flex-col">
      {{-- 大頭貼 --}}
      <div class="flex items-center space-x-4 text-base">
        @if ($userId !== 0)
          <a
            href="{{ route('users.show', ['userId' => $userId]) }}"
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

        <time
          class="hidden text-gray-400 md:block"
          datetime="{{ $createdAt->toDateString() }}"
        >{{ $createdAt->format('Y 年 m 月 d 日') }}</time>

        @if ($isEdited)
          <span class="text-gray-400">(已編輯)</span>
        @endif
      </div>

      {{-- 留言 --}}
      <div class="comment-body">
        {!! $this->convertedBody !!}
      </div>

      <div class="flex items-center space-x-6 text-base text-gray-400">
        @auth
          @if (auth()->id() === $userId)
            <button
              class="flex items-center hover:text-gray-500 dark:hover:text-gray-300"
              type="button"
              x-on:click="setEditComment"
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

        @if ($currentLayer < $maxLayer)
          <button
            class="flex items-center hover:text-gray-500 dark:hover:text-gray-300"
            type="button"
            {{-- the comment group name should be full name --}}
            x-on:click="openCreateCommentModal"
          >
            <x-icon.reply-fill class="w-4" />
            <span class="ml-2">回覆</span>
          </button>
        @endif
      </div>
    </div>
  </x-dashed-card>

  @if ($currentLayer < $maxLayer)
    <div
      class="relative pl-8 before:absolute before:left-2 before:h-full before:w-1 before:rounded-full before:bg-green-400/20 before:contain-none dark:before:bg-indigo-500/20"
      id="children-{{ $commentId }}"
    >
      <livewire:shared.comments.comment-group
        :$maxLayer
        :current-layer="$currentLayer + 1"
        :post-id="$postId"
        :post-author-id="$postAuthorId"
        :comment-group-name="$commentId . '-new-group'"
      />

      {{-- If this comment has no sub-messages,
      do not render the next level of sub-comment list to avoid redundant SQL queries. --}}
      @if ($hasChildren)
        <livewire:shared.comments.comment-list
          :$maxLayer
          :current-layer="$currentLayer + 1"
          :per-page="$childrenPerPage"
          :$postId
          :$postAuthorId
          :parent-id="$commentId"
          :comment-list-name="$commentId"
        />
      @endif
    </div>
  @endif
</div>
