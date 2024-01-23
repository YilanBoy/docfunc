<div class="sticky top-1/2 flex -translate-y-1/2 flex-col space-y-2">
  {{-- Home --}}
  <a
    class="group flex h-14 w-14 items-center justify-center text-gray-500 dark:text-gray-400"
    href="{{ route('posts.index') }}"
    role="button"
    wire:navigate
  >
    <span class="text-2xl transition duration-150 ease-in group-hover:rotate-12 group-hover:scale-125">
      <i class="bi bi-house-fill"></i>
    </span>
  </a>

  <!-- Facebook share button -->
  <button
    class="group flex h-14 w-14 items-center justify-center text-gray-500 dark:text-gray-400"
    data-sharer="facebook"
    data-hashtag="{{ config('app.name') }}"
    data-url="{{ request()->fullUrl() }}"
    type="button"
  >
    <span class="text-2xl transition duration-150 ease-in group-hover:rotate-12 group-hover:scale-125">
      <i class="bi bi-facebook"></i>
    </span>
  </button>

  <!-- Twitter share button -->
  <button
    class="group flex h-14 w-14 items-center justify-center text-gray-500 dark:text-gray-400"
    data-sharer="twitter"
    data-title="{{ $postTitle }}"
    data-hashtags="{{ config('app.name') }}"
    data-url="{{ request()->fullUrl() }}"
    type="button"
  >
    <span class="text-2xl transition duration-150 ease-in group-hover:rotate-12 group-hover:scale-125">
      <i class="bi bi-twitter-x"></i>
    </span>
  </button>

  <!-- Copy link button -->
  <x-tooltip :tooltip-position="'right'">
    <button
      class="group flex h-14 w-14 items-center justify-center text-gray-500 dark:text-gray-400"
      data-clipboard="{{ request()->fullUrl() }}"
      type="button"
    >
      <span class="text-2xl transition duration-150 ease-in group-hover:rotate-12 group-hover:scale-125">
        <i class="bi bi-link-45deg"></i>
      </span>
    </button>
  </x-tooltip>

  {{-- 編輯文章 --}}
  @if (auth()->id() === $authorId)
    <div class="h-[2px] w-14 bg-gray-300 dark:bg-gray-600"></div>

    <a
      class="group flex h-14 w-14 items-center justify-center text-gray-500 dark:text-gray-400"
      href="{{ route('posts.edit', ['post' => $postId]) }}"
      role="button"
      wire:navigate
    >
      <span class="text-2xl transition duration-150 ease-in group-hover:rotate-12 group-hover:scale-125">
        <i class="bi bi-pencil-square"></i>
      </span>
    </a>

    {{-- 刪除 --}}
    <button
      class="group flex h-14 w-14 items-center justify-center text-gray-500 dark:text-gray-400"
      type="button"
      title="刪除文章"
      wire:confirm="你確定要刪除文章嗎？（7 天之內可以還原）"
      wire:click="deletePost({{ $postId }})"
    >
      <span class="text-2xl transition duration-150 ease-in group-hover:rotate-12 group-hover:scale-125">
        <i class="bi bi-trash-fill"></i>
      </span>
    </button>
  @endif
</div>
