@script
  <script>
    Alpine.data('commentCard', () => ({
      openEditCommentModal() {
        this.$dispatch('open-edit-comment-modal', {
          id: this.$root.dataset.commentId,
          body: this.$root.dataset.commentBody,
        })
      },
      openCreateCommentModal() {
        this.$dispatch('open-create-comment-modal', {
          parentId: this.$root.dataset.commentId,
          replyTo: this.$root.dataset.commentUserName
        })
      }
    }));
  </script>
@endscript

<div
  data-comment-id="{{ $commentId }}"
  data-comment-body="{{ $commentBody }}"
  data-comment-user-name="{{ $commentUserName }}"
  x-data="commentCard"
>
  <x-dashed-card class="mt-6">
    <div class="flex flex-col">
      <div class="flex items-center space-x-4 text-base">
        @if (!is_null($commentUserId))
          <a
            href="{{ route('users.show', ['id' => $commentUserId]) }}"
            wire:navigate
          >
            <img
              class="size-10 rounded-full hover:ring-2 hover:ring-blue-400"
              src="{{ $commentUserGravatarUrl }}"
              alt="{{ $commentUserName }}"
            >
          </a>
        @else
          <x-icon.question-circle-fill class="size-10 text-gray-300 dark:text-gray-500" />
        @endif

        <span class="dark:text-gray-50">{{ $commentUserName }}</span>

        <time
          class="hidden text-gray-400 md:block"
          datetime="{{ date('d-m-Y', strtotime($commentCreatedAt)) }}"
        >{{ date('Y 年 m 月 d 日', strtotime($commentCreatedAt)) }}</time>

        @if ($commentIsEdited)
          <span class="text-gray-400">(已編輯)</span>
        @endif
      </div>

      <div class="rich-text">
        {!! $this->convertedBody !!}
      </div>

      <div class="flex items-center justify-end gap-6 text-base text-gray-400">
        @auth
          @if (auth()->id() === $commentUserId)
            <button
              class="flex items-center hover:text-gray-500 dark:hover:text-gray-300"
              type="button"
              x-on:click="openEditCommentModal"
            >
              <x-icon.pencil class="w-4" />
              <span class="ml-2">編輯</span>
            </button>
          @endif

          @if (in_array(auth()->id(), [$commentUserId, $postUserId]))
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
      class="relative pl-4 before:absolute before:left-0 before:top-0 before:h-full before:w-1 before:rounded-full before:bg-emerald-400/20 before:contain-none dark:before:bg-indigo-500/20 md:pl-8"
      id="children-{{ $commentId }}"
    >
      <livewire:shared.comments.comment-group
        :post-id="$postId"
        :post-user-id="$postUserId"
        :max-layer="$maxLayer"
        :current-layer="$currentLayer + 1"
        :parent-id="$commentId"
        :comment-group-name="$commentId . '-new-comment-group'"
      />

      {{-- If this comment has no sub-messages,
      do not render the next level of sub-comment list to avoid redundant SQL queries. --}}
      @if ($commentHasChildren)
        <livewire:shared.comments.comment-list
          :post-id="$postId"
          :post-user-id="$postUserId"
          :max-layer="$maxLayer"
          :current-layer="$currentLayer + 1"
          :parent-id="$commentId"
          :per-page="$childrenPerPage"
          :comment-list-name="$commentId . '-comment-list'"
          :order="App\Enums\CommentOrder::OLDEST"
        />
      @endif
    </div>
  @endif
</div>
