{{-- 留言列表 --}}
<div
  x-data="{ currentScrollY: 0 }"
  x-init="// after editing comment or loading more comments, reload the scripts
  Livewire.hook('message.processed', (message) => {
      // if the method is 'showMore', scroll to the previous position
      if (message.updateQueue[0].method === 'showMore') {
          window.scrollTo(0, currentScrollY);
      }

      document.querySelectorAll('#comments pre code').forEach((element) => {
          window.hljs.highlightElement(element)
      })

      window.codeBlockCopyButton('#comments pre')
  })"
  id="comments"
  class="w-full"
>

  @for ($offset = 0, $groupId = 0; $offset < $count; $offset += $perPage, $groupId++)
    <livewire:comments.comment-group
      :post-id="$postId"
      :per-page="$perPage"
      :offset="$offset"
      :group-id="$groupId"
      :wire:key="'comment-group-' . $groupId"
    />
  @endfor

  @if ($showMoreButtonIsActive)
    <div class="mt-6 flex items-center justify-center">
      <button
        {{-- when click the button and update the DOM, make windows.scrollY won't change --}}
        x-on:mousedown="currentScrollY = window.scrollY"
        wire:mouseup="showMore"
        class="text-lg dark:text-gray-50"
      >
        顯示更多
      </button>
    </div>
  @endif
</div>
