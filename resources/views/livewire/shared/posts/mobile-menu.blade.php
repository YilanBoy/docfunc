<div class="mt-4 flex items-center space-x-6 text-base text-gray-400 xl:hidden">
  <a
    class="flex items-center hover:text-gray-500 dark:hover:text-gray-300"
    href="{{ route('posts.edit', ['post' => $postId]) }}"
  >
    <x-icon.pencil class="w-4" />
    <span class="ml-2">編輯</span>
  </a>

  <button
    class="flex items-center hover:text-gray-500 dark:hover:text-gray-300"
    type="button"
    wire:confirm="你確定要刪除文章嗎？（7 天之內可以還原）"
    wire:click="destroy({{ $postId }})"
  >
    <x-icon.trash class="w-4" />
    <span class="ml-2">刪除</span>
  </button>
</div>
