@script
  <script>
    Alpine.data('commentList', () => ({
      listeners: [],
      currentScrollY: 0,
      init() {
        this.listeners.push(
          Livewire.hook('commit.prepare', ({
            component
          }) => {
            if (component.name === 'shared.comments.comment-list') {
              this.currentScrollY = window.scrollY;
            }
          }),
          Livewire.hook('morph.updated', ({
            component
          }) => {
            if (component.name === 'shared.comments.comment-list') {
              // make sure scroll position will update after dom updated
              queueMicrotask(() => {
                window.scrollTo({
                  top: this.currentScrollY,
                  behavior: 'instant'
                });
              });
            }
          })
        );
      },
      destroy() {
        this.listeners.forEach((listener) => {
          listener();
        });
      }
    }));
  </script>
@endscript

{{-- 留言列表 --}}
<div
  class="w-full"
  x-data="commentList"
>
  @foreach ($commentIdsList as $commentIds)
    <livewire:shared.comments.comment-group
      :$maxLayer
      :$currentLayer
      :$postId
      :$postAuthorId
      :$parentId
      :$commentIds
      :comment-group-name="$commentIds[0] . '-comment-group'"
      :$order
      :key="$commentIds[0] . '-comment-group'"
    />
  @endforeach

  @if ($showMoreButtonIsActive)
    <div class="mt-6 flex w-full items-center justify-center">
      <button
        class="rounded-lg bg-emerald-50 px-3.5 py-2.5 text-sm text-emerald-600 shadow-sm hover:bg-emerald-100 dark:bg-lividus-500 dark:text-gray-50 dark:hover:bg-lividus-400"
        type="button"
        wire:click="showMoreComments"
      >
        <x-icon.animate-spin
          class="mr-2 size-5"
          wire:loading
        />
        <span>顯示更多留言</span>
      </button>
    </div>
  @endif
</div>
