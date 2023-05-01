{{-- 留言列表 --}}
<div
  x-data="{ currentScrollY: 0 }"
  x-init="// after editing comment or loading more comments, reload the scripts
  Livewire.hook('message.processed', (message) => {
      // if the method is 'showMore', scroll to the previous position
      if (message.updateQueue[0].method === 'showMore') {
          window.scrollTo(0, currentScrollY);
      }

      document.querySelectorAll('#comments pre code:not(.hljs)').forEach((element) => {
          window.hljs.highlightElement(element)
      })

      window.codeBlockCopyButton('#comments pre')
  })"
  id="comments"
  class="w-full"
>

  @for ($offset = 0; $offset < $count; $offset += $perPage)
    <livewire:comments.comment-group
      :post-id="$postId"
      :per-page="$perPage"
      :offset="$offset"
      :wire:key="'comment-group-' . $offset"
    />
  @endfor

  @if ($showMoreButtonIsActive)
    <div class="mt-6 flex items-center justify-center">
      <button
        {{-- when click the button and update the DOM, make windows.scrollY won't change --}}
        x-on:mousedown="currentScrollY = window.scrollY"
        wire:mouseup="showMore"
        class="relative text-lg dark:text-gray-50"
      >
        <span>顯示更多</span>
        <div
          wire:loading
          class="absolute -right-8 top-1.5"
        >
          <svg
            class="h-5 w-5 animate-spin text-gray-700 dark:text-gray-50"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
          >
            <circle
              class="opacity-25"
              cx="12"
              cy="12"
              r="10"
              stroke="currentColor"
              stroke-width="4"
            ></circle>
            <path
              class="opacity-75"
              fill="currentColor"
              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
            ></path>
          </svg>
        </div>
      </button>
    </div>
  @endif
</div>
