{{-- 留言列表 --}}
<div id="comments" class="w-full">

  @for ($offset = 0; $offset < $count; $offset += $perPage)
    <livewire:comments.comment-group
      :postId="$postId"
      :perPage="$perPage"
      :offset="$offset"
      :wire:key="'comment-group-' . $offset"
    />
  @endfor

  @if ($showMoreButtonIsActive)
    <div class="flex items-center justify-center mt-6">
      <button
        wire:click="showMore"
        class="text-lg dark:text-gray-50"
      >
        顯示更多
      </button>
    </div>
  @endif
</div>
