<div class="p-2">
  {{-- posts list --}}
  @foreach ($posts as $post)
    <div
      class="flex flex-col justify-between rounded px-2 py-2 transition duration-100 hover:bg-gray-100 dark:hover:bg-gray-700 md:flex-row"
      {{-- in this list, these post attribue will be change in the loop, so we have to track them down --}}
      wire:key="{{ $post->id . $post->is_private . $post->deleted_at }}"
    >
      <i class="bi bi-arrow-right-short dark:text-gray-400"></i>

      <div class="ml-2 w-full">
        @if ($post->trashed())
          <span class="text-red-400 line-through">{{ $post->title . ' (已刪除)' }}</span>
        @elseif ($post->is_private)
          <a
            class="duration-200 ease-out hover:text-gray-900 hover:underline dark:text-gray-400 dark:hover:text-gray-50"
            href="{{ $post->link_with_slug }}"
            wire:navigate
          >
            {{ $post->title . ' (未公開)' }}
          </a>
        @else
          <a
            class="duration-200 ease-out hover:text-gray-900 hover:underline dark:text-gray-400 dark:hover:text-gray-50"
            href="{{ $post->link_with_slug }}"
            wire:navigate
          >
            {{ $post->title }}
          </a>
        @endif
      </div>

      @if ($post->user_id === auth()->id())
        <div class="ml-2 flex items-center space-x-3">

          {{-- restore --}}
          @if ($post->trashed())
            <button
              class="text-gray-500 duration-200 ease-out hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-50"
              type="button"
              wire:loading.attr="disabled"
              wire:confirm="你確定要還原該文章？"
              wire:click="restore({{ $post->id }})"
            >
              <i class="bi bi-arrow-counterclockwise"></i>
            </button>
          @else
            {{-- private --}}
            @if ($post->is_private)
              <button
                class="text-gray-500 duration-200 ease-out hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-50"
                type="button"
                wire:loading.attr="disabled"
                wire:confirm="你確定要將該文章設為公開？"
                wire:click="postPrivateToggle({{ $post->id }})"
              >
                <i class="bi bi-lock-fill"></i>
              </button>
            @else
              <button
                class="text-gray-500 duration-200 ease-out hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-50"
                type="button"
                wire:loading.attr="disabled"
                wire:confirm="你確定要將該文章設為不公開？"
                wire:click="postPrivateToggle({{ $post->id }})"
              >

                <i class="bi bi-unlock-fill"></i>
              </button>
            @endif

            {{-- edit --}}
            <a
              class="text-gray-500 duration-200 ease-out hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-50"
              href="{{ route('posts.edit', ['post' => $post->id]) }}"
              role="button"
              wire:navigate
            >
              <i class="bi bi-pencil-square"></i>
            </a>

            {{-- delete --}}
            <button
              class="text-red-400 duration-200 ease-out hover:text-red-700 dark:hover:text-red-200"
              type="button"
              wire:loading.attr="disabled"
              wire:confirm="你確定要刪除文章嗎？（7 天之內可以還原）"
              wire:click.stop="deletePost({{ $post->id }})"
            >
              <i class="bi bi-x-lg"></i>
            </button>
          @endif

        </div>
      @endif
    </div>
  @endforeach
</div>
