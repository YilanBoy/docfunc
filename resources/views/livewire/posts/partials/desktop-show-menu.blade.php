<div class="sticky flex flex-col space-y-2 -translate-y-1/2 top-1/2">
  {{-- Home --}}
  <a
    role="button"
    href="{{ route('posts.index') }}"
    class="flex items-center justify-center text-gray-400 w-14 h-14 group"
  >
      <span class="text-2xl transition duration-150 ease-in group-hover:scale-125 group-hover:rotate-12">
        <i class="bi bi-house-fill"></i>
      </span>
  </a>

  <!-- Facebook share button -->
  <button
    type="button"
    data-sharer="facebook"
    data-hashtag="{{ config('app.name') }}"
    data-url="{{ request()->fullUrl() }}"
    class="flex items-center justify-center text-gray-400 w-14 h-14 group"
  >
      <span class="text-2xl transition duration-150 ease-in group-hover:scale-125 group-hover:rotate-12">
        <i class="bi bi-facebook"></i>
      </span>
  </button>

  <!-- Twitter share button -->
  <button
    type="button"
    data-sharer="twitter"
    data-title="{{ $postTitle }}"
    data-hashtags="{{ config('app.name') }}"
    data-url="{{ request()->fullUrl() }}"
    class="flex items-center justify-center text-gray-400 w-14 h-14 group"
  >
      <span class="text-2xl transition duration-150 ease-in group-hover:scale-125 group-hover:rotate-12">
        <i class="bi bi-twitter"></i>
      </span>
  </button>

  {{-- 編輯文章 --}}
  @if (auth()->id() === $authorId)
    <div class="h-[2px] w-14 bg-gray-300 dark:bg-gray-600"></div>

    <a
      role="button"
      href="{{ route('posts.edit', ['id' => $postId]) }}"
      class="flex items-center justify-center text-gray-400 w-14 h-14 group"
    >
      <span class="text-2xl transition duration-150 ease-in group-hover:scale-125 group-hover:rotate-12">
        <i class="bi bi-pencil-square"></i>
      </span>
    </a>

    {{-- 刪除 --}}
    <button
      onclick="confirm('你確定要刪除文章嗎？（7 天之內可以還原）') || event.stopImmediatePropagation()"
      wire:click="deletePost({{ $postId }})"
      type="button"
      title="刪除文章"
      class="flex items-center justify-center text-gray-400 w-14 h-14 group"
    >
      <span class="text-2xl transition duration-150 ease-in group-hover:scale-125 group-hover:rotate-12">
        <i class="bi bi-trash-fill"></i>
      </span>
    </button>
  @endif
</div>
