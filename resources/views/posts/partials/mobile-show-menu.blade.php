<div
  x-data="{ editMenuIsOpen: false }"
  class="relative xl:hidden"
>
  <div>
    <button
      x-on:click="editMenuIsOpen = ! editMenuIsOpen"
      x-on:click.outside="editMenuIsOpen = false"
      x-on:keydown.escape.window="editMenuIsOpen = false"
      type="button"
      class="text-2xl text-gray-400 hover:text-gray-700 focus:text-gray-700 dark:hover:text-gray-50 dark:focus:text-gray-50"
      aria-expanded="false"
      aria-haspopup="true"
    >
      <i class="bi bi-three-dots-vertical"></i>
    </button>
  </div>

  <div
    x-cloak
    x-show="editMenuIsOpen"
    x-transition.origin.top.right
    class="absolute right-0 z-10 w-48 p-2 mt-2 text-gray-700 rounded-md shadow-lg bg-gray-50 ring-1 ring-black ring-opacity-20 dark:bg-gray-700 dark:text-gray-50 dark:ring-gray-500"
    role="menu"
    aria-orientation="vertical"
    tabindex="-1"
  >
    {{-- 編輯文章 --}}
    <a
      href="{{ route('posts.edit', ['post' => $post->id]) }}"
      role="menuitem"
      tabindex="-1"
      class="block px-4 py-2 rounded-md hover:bg-gray-200 active:bg-gray-100 dark:hover:bg-gray-600"
    >
      <i class="bi bi-pencil-fill"></i><span class="ml-2">編輯</span>
    </a>

    {{-- 軟刪除 --}}
    <button
      x-on:click="
        if (confirm('您確定標記此文章為刪除狀態嗎？（30 天內還可以還原）')) {
          document.getElementById('soft-delete-post').submit()
        }
      "
      type="button"
      role="menuitem"
      tabindex="-1"
      class="flex items-start w-full px-4 py-2 rounded-md hover:bg-gray-200 active:bg-gray-100 dark:hover:bg-gray-600"
    >
      <i class="bi bi-file-earmark-x-fill"></i><span class="ml-2">刪除標記</span>
    </button>
  </div>
</div>
