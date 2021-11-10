{{-- 留言列表 --}}
<div id="comments" class="w-full mt-6">

    <div class="w-full">
        @for ($offset = 0; $offset < $count; $offset += $perPage)
            <livewire:comments-group :post="$post" :perPage="$perPage" :offset="$offset"
                                     wire:key="comments-group-{{ $offset }}"/>
        @endfor
    </div>

    @if ($showMoreButtonIsActive)
        <div class="mt-6 flex justify-center items-center">
            <button wire:click="showMore" class="dark:text-gray-50 text-lg">顯示更多留言</button>
        </div>
    @endif
</div>
