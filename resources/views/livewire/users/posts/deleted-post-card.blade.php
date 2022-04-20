<x-card class="flex flex-col justify-between border-2 border-red-400 group md:flex-row">
  {{-- 文章 --}}
  <div class="flex flex-col justify-between w-full">
    <span class="text-red-400">文章將於{{ $destroyDate }}刪除</span>

    {{-- 文章標題 --}}
    <span class="mt-2 text-xl font-semibold md:mt-0 dark:text-gray-50">
      <span>{{ $title }}</span>
    </span>

    {{-- 文章相關資訊 --}}
    <div class="flex items-center mt-2 space-x-2 text-sm text-gray-500 dark:text-gray-300">
      {{-- 文章分類資訊 --}}
      <div>
        <span title="{{ $categoryName }}">
          <i class="{{ $categoryIcon }}"></i><span class="ml-2">{{ $categoryName }}</span>
        </span>
      </div>
      <div>&bull;</div>
      {{-- 文章發布時間 --}}
      <div>
        <span title="文章發布於：{{ $createdAt }}">
          <i class="bi bi-clock-fill"></i><span class="ml-2">{{ $createdAtDiffForHumans }}</span>
        </span>
      </div>
      <div>&bull;</div>
      <div>
        {{-- 文章留言數 --}}
        <span>
          <i class="bi bi-chat-square-text-fill"></i><span class="ml-2">{{ $commentCount }}</span>
        </span>
      </div>
    </div>
  </div>

  {{-- 還原文章隱藏表單 --}}
  <form
    id="restore-post-{{ $postId }}"
    action="{{ route('posts.restore', ['id' => $postId]) }}"
    method="POST"
    class="hidden"
  >
    @csrf
  </form>

  {{-- 完全刪除隱藏表單 --}}
  <form
    id="destroy-post-{{ $postId }}"
    action="{{ route('posts.forceDelete', ['id' => $postId]) }}"
    method="POST"
    class="hidden"
  >
    @csrf
    @method('DELETE')
  </form>

  <div class="flex items-center mt-2 space-x-2 md:mt-0">
    {{-- 還原文章 --}}
    <button
      x-on:click.stop="
        if (confirm('您確定恢復此文章嗎？')) {
          document.getElementById('restore-post-{{ $postId }}').submit()
        }
      "
      type="button"
      class="inline-flex items-center justify-center w-10 h-10 transition duration-150 ease-in-out bg-blue-500 border border-transparent rounded-md text-gray-50 hover:bg-blue-600 active:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring ring-blue-300"
    >
      <i class="bi bi-file-earmark-check-fill"></i>
    </button>

    {{-- 完全刪除 --}}
    <button
      x-on:click.stop="
        if(confirm('您確定要完全刪除此文章嗎？（此動作無法復原）')) {
          document.getElementById('destroy-post-{{ $postId }}').submit()
        }
      "
      type="button"
      class="inline-flex items-center justify-center w-10 h-10 transition duration-150 ease-in-out bg-red-500 border border-transparent rounded-md text-gray-50 hover:bg-red-600 active:bg-red-700 focus:outline-none focus:border-red-700 focus:ring ring-red-300"
    >
      <i class="bi bi-trash-fill"></i>
    </button>
  </div>
</x-card>
