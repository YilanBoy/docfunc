@script
  <script>
    Alpine.data('showPostDropdowns', () => ({
      editMenuIsOpen: false,
      toggleEditMenu() {
        this.editMenuIsOpen = !this.editMenuIsOpen;
      },
      closeEditMenu() {
        this.editMenuIsOpen = false;
      }
    }));
  </script>
@endscript

<div
  class="relative xl:hidden"
  x-data="showPostDropdowns"
>
  <div>
    <button
      class="text-2xl text-gray-400 hover:text-gray-700 focus:text-gray-700 dark:hover:text-gray-50 dark:focus:text-gray-50"
      type="button"
      aria-expanded="false"
      aria-haspopup="true"
      x-on:click="toggleEditMenu"
      x-on:click.outside="closeEditMenu"
      x-on:keydown.escape.window="closeEditMenu"
    >
      <x-icon.three-dots-vertical class="w-6" />
    </button>
  </div>

  <div
    class="absolute right-0 z-10 mt-2 w-48 rounded-md bg-gray-50 p-2 text-gray-700 shadow-lg ring-1 ring-black ring-opacity-20 dark:bg-gray-800 dark:text-gray-50 dark:ring-gray-600"
    role="menu"
    aria-orientation="vertical"
    tabindex="-1"
    x-cloak
    x-show="editMenuIsOpen"
    x-transition.origin.top.right
  >
    {{-- 編輯文章 --}}
    <a
      class="flex items-center rounded-md px-4 py-2 hover:bg-gray-200 active:bg-gray-100 dark:hover:bg-gray-700"
      href="{{ route('posts.edit', ['post' => $postId]) }}"
      role="menuitem"
      tabindex="-1"
      wire:navigate
    >
      <x-icon.pencil class="w-4" />
      <span class="ml-2">編輯</span>
    </a>

    {{-- 軟刪除 --}}
    <button
      class="flex w-full items-center rounded-md px-4 py-2 hover:bg-gray-200 active:bg-gray-100 dark:hover:bg-gray-700"
      type="button"
      role="menuitem"
      tabindex="-1"
      wire:confirm="你確定要刪除文章嗎？（7 天之內可以還原）"
      wire:click="deletePost({{ $postId }})"
    >
      <x-icon.file-earmark-x class="w-4" />
      <span class="ml-2">刪除</span>
    </button>
  </div>
</div>
