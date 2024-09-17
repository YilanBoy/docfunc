@script
  <script>
    Alpine.data('comments', () => ({
      listeners: [],
      observers: [],
      currentScrollY: 0,
      init() {
        this.listeners.push(
          Livewire.hook('commit.prepare', ({
            component
          }) => {
            if (component.name === 'shared.comments.comments') {
              this.currentScrollY = window.scrollY;
            }
          }),
          Livewire.hook('morph.updated', ({
            component
          }) => {
            if (component.name === 'shared.comments.comments') {
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

        this.observers.push(commentsObserver);
      },
      destroy() {
        this.listeners.forEach((listener) => {
          listener();
        });

        this.observers.forEach((observer) => {
          observer.disconnect();
        })
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
    :key="'group-new'"
  />

  @foreach ($IdsByFirstId as $groupId => $ids)
    <livewire:shared.comments.comment-group
      :$postId
      :$postAuthorId
      :$ids
      :$groupId
      :key="'group-' . $groupId"
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
