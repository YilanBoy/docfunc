@script
  <script>
    Alpine.data('userComments', () => ({
      init() {
        hljs.highlightAll();

        let userCommentsObserver = new MutationObserver(() => {
          this.$refs.userComments
            .querySelectorAll('pre code:not(.hljs)')
            .forEach((element) => {
              hljs.highlightElement(element);
            });
        });

        userCommentsObserver.observe(this.$refs.userComments, {
          childList: true,
          subtree: true,
          attributes: true,
          characterData: false
        });

        let disconnectUserCommentsObserver = () => {
          userCommentsObserver.disconnect();
          document.removeEventListener('livewire:navigating', disconnectUserCommentsObserver);
        };

        document.addEventListener('livewire:navigating', disconnectUserCommentsObserver);
      }
    }));
  </script>
@endscript

{{-- 會員留言 --}}
<div
  class="space-y-6"
  x-data="userComments"
  x-ref="userComments"
>
  @foreach ($comments as $comment)
    <x-dashed-card
      class="group relative max-h-64 cursor-pointer overflow-hidden after:absolute after:inset-x-0 after:bottom-0 after:h-1/2 after:bg-gradient-to-b after:from-transparent after:to-gray-100 dark:after:to-gray-800"
      wire:key="comment-{{ $comment->id }}"
    >
      <a
        class="absolute right-0 top-0 z-20 block h-full w-full bg-transparent"
        href="{{ $comment->post->link_with_slug }}#comments"
        wire:navigate
      ></a>

      <span class="group-gradient-underline-grow text-xl dark:text-gray-50">
        {{ $comment->post->title }}
      </span>

      {{-- 留言 --}}
      <div class="comment-body">
        {!! $comment->body !!}
      </div>

      <div
        class="absolute bottom-3 right-3 z-10 flex items-center rounded-lg bg-emerald-600 px-2 py-1 text-sm text-gray-50 dark:bg-lividus-600"
      >
        <x-icon.clock class="w-4" />
        <span class="ml-2">{{ $comment->created_at->diffForHumans() }}</span>
      </div>
    </x-dashed-card>
  @endforeach

  {{ $comments->onEachSide(1)->links() }}
</div>
