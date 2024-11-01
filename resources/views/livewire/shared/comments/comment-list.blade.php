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
  @foreach ($commentIdsGroupByFirstId as $firstId => $commentIds)
    <livewire:shared.comments.comment-group
      :$maxLayer
      :$currentLayer
      :$postId
      :$postAuthorId
      :$parentId
      :$commentIds
      :comment-group-name="$firstId . '-comment-group'"
      :key="$firstId . '-comment-group'"
    />
  @endforeach

  @if ($showMoreButtonIsActive)
    <button
      class="mt-6 flex w-full items-center justify-center rounded-xl border-2 border-dashed border-gray-500 p-2 text-lg text-gray-500 transition-colors duration-300 ease-in-out hover:bg-gray-500 hover:text-gray-50"
      type="button"
      wire:click="showMoreComments"
    >
      <x-icon.animate-spin
        class="w-5"
        wire:loading
      />
      <span class="ml-2">顯示更多留言</span>
    </button>
  @endif
</div>
