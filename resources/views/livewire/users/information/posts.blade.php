<div>
  {{-- posts list --}}
  @foreach ($posts as $post)
    <div
      class="flex flex-col justify-between p-5 pt-0 md:flex-row"
      {{-- in this list, these post attribue will be change in the loop, so we have to track them down --}}
      wire:key="{{ $post->id . $post->is_private . $post->deleted_at }}"
    >
      <div class="text-gray-400">
        <i class="bi bi-arrow-right-short"></i>
        @if ($post->trashed())
          <span class="ml-2 text-red-400 line-through">{{ $post->title . ' (已刪除)' }}</span>
        @elseif ($post->is_private)
          <a
            class="ml-2 duration-200 ease-out hover:text-gray-700 hover:underline dark:hover:text-gray-300"
            href="{{ $post->link_with_slug }}"
          >
            {{ $post->title . ' (未公開)' }}
          </a>
        @else
          <a
            class="ml-2 duration-200 ease-out hover:text-gray-700 hover:underline dark:hover:text-gray-300"
            href="{{ $post->link_with_slug }}"
          >
            {{ $post->title }}
          </a>
        @endif
      </div>

      @if ($post->user_id === auth()->id())
        <div class="flex items-center space-x-2">

          {{-- restore --}}
          @if ($post->trashed())
            <button
              class="text-gray-400 duration-200 ease-out hover:text-gray-700 dark:hover:text-gray-200"
              type="button"
              wire:loading.attr="disabled"
              onclick="confirm('你確定要還原該文章？') || event.stopImmediatePropagation()"
              wire:click="restore({{ $post->id }})"
            >
              <i class="bi bi-arrow-counterclockwise"></i>
            </button>
          @else
            {{-- private --}}
            @if ($post->is_private)
              <button
                class="text-gray-400 duration-200 ease-out hover:text-gray-700 dark:hover:text-gray-200"
                type="button"
                wire:loading.attr="disabled"
                onclick="confirm('你確定要將該文章設為公開？') || event.stopImmediatePropagation()"
                wire:click="postPrivateToggle({{ $post->id }})"
              >
                <i class="bi bi-lock-fill"></i>
              </button>
            @else
              <button
                class="text-gray-400 duration-200 ease-out hover:text-gray-700 dark:hover:text-gray-200"
                type="button"
                wire:loading.attr="disabled"
                onclick="confirm('你確定要將該文章設為不公開？') || event.stopImmediatePropagation()"
                wire:click="postPrivateToggle({{ $post->id }})"
              >

                <i class="bi bi-unlock-fill"></i>
              </button>
            @endif

            {{-- edit --}}
            <a
              class="text-gray-400 duration-200 ease-out hover:text-gray-700 dark:hover:text-gray-200"
              href="{{ route('posts.edit', ['post' => $post->id]) }}"
              role="button"
            >
              <i class="bi bi-pencil-square"></i>
            </a>

            {{-- delete --}}
            <button
              class="text-red-400 duration-200 ease-out hover:text-red-700 dark:hover:text-red-200"
              type="button"
              wire:loading.attr="disabled"
              onclick="confirm('你確定要刪除文章嗎？（7 天之內可以還原）') || event.stopImmediatePropagation()"
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
