@script
  <script>
    Alpine.data('comments', () => ({
      currentScrollY: 0,
      init() {
        Livewire.hook('commit.prepare', ({
          component
        }) => {
          if (component.name === 'shared.comments.comments') {
            this.currentScrollY = window.scrollY;
          }
        });

        Livewire.hook('morph.updated', ({
          component
        }) => {
          if (component.name === 'shared.comments.comments') {
            window.scrollTo(0, this.currentScrollY);
          }
        });

        let commentsObserver = new MutationObserver(() => {
          this.$refs.comments
            .querySelectorAll('pre code:not(.hljs)')
            .forEach((element) => {
              hljs.highlightElement(element);
            });
        });

        commentsObserver.observe(this.$refs.comments, {
          childList: true,
          subtree: true,
          attributes: true,
        });

        let disconnectCommentsObserver = () => {
          commentsObserver.disconnect();
          document.removeEventListener('livewire:navigating', disconnectCommentsObserver);
        };

        document.addEventListener('livewire:navigating', disconnectCommentsObserver);
      }
    }));
  </script>
@endscript

{{-- 留言列表 --}}
<div
  class="w-full"
  id="comments"
  x-data="comments"
  x-ref="comments"
>
  {{-- new comment will show here --}}
  <livewire:shared.comments.comment-group
    :$postId
    :$postAuthorId
    :wire:key="'group-new'"
  />

  @foreach ($IdsByFirstId as $groupId => $ids)
    <livewire:shared.comments.comment-group
      :$postId
      :$postAuthorId
      :$ids
      :$groupId
      :wire:key="'group-'.$groupId"
    />
  @endforeach

  @if ($showMoreButtonIsActive)
    <button
      class="mt-6 flex w-full items-center justify-center rounded-xl p-2 text-lg transition-colors duration-150 ease-in-out hover:bg-gray-300 dark:text-gray-50 dark:hover:bg-gray-700"
      type="button"
      wire:click="showMore"
    >
      <x-icon.animate-spin
        class="w-5"
        wire:loading
      />
      <span class="ml-2">顯示更多</span>
    </button>
  @endif
</div>
