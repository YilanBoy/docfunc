{{-- 留言列表 --}}
<div id="comments" class="w-full xl:w-2/3">

  <div class="w-full">
    @for ($offset = 0; $offset < $count; $offset += $perPage)
      <livewire:comments.comments-group
        :postId="$postId"
        :perPage="$perPage"
        :offset="$offset"
        wire:key="comments-group-{{ $offset }}"
      />
    @endfor
  </div>

  @if ($showMoreButtonIsActive)
    <div class="flex items-center justify-center mt-6">
      <button
        wire:click="showMore"
        class="text-lg dark:text-gray-50"
      >顯示更多留言</button>
    </div>
  @endif
</div>
