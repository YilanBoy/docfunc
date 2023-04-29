{{-- 留言列表 --}}
<div
  x-data
  x-init="// after editing comment, reload the scripts
  Livewire.hook('message.processed', (el, component) => {
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
        wire:click="showMore"
        class="text-lg dark:text-gray-50"
      >
        顯示更多
      </button>
    </div>
  @endif
</div>
