{{-- 留言列表 --}}
<div id="comments" class="w-full space-y-6 mt-6">

    <div class="w-full">
        @for ($offset = 0; $offset < $count; $offset += $perPage)
            <livewire:comments-group :post="$post" :perPage="$perPage" :offset="$offset" wire:key="comments-group-{{ $offset }}" />
        @endfor
    </div>

    @if ($showMoreButtonIsActive)
         <button wire:click="showMore" class="w-full dark:text-gray-50">顯示更多留言</button>
    @endif
</div>
