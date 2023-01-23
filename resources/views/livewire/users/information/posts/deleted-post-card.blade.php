<x-card class="flex flex-col justify-between border-2 border-red-400 group md:flex-row">
  {{-- 文章 --}}
  <div class="flex flex-col justify-between w-full">
    <span class="text-red-400">文章將於{{ $postWillDeletedAtDiffForHuman }}刪除</span>

    {{-- 文章標題 --}}
    <span class="mt-2 text-xl font-semibold md:mt-0 dark:text-gray-50">
      <span>{{ $postTitle }}</span>
    </span>

    {{-- 文章相關資訊 --}}
    <div class="flex items-center mt-2 space-x-2 text-base text-neutral-400">
      {{-- 文章分類資訊 --}}
      <div>
        <span title="{{ $categoryName }}">
          <i class="{{ $categoryIcon }}"></i><span class="ml-2">{{ $categoryName }}</span>
        </span>
      </div>
      <div>&bull;</div>
      {{-- 文章發布時間 --}}
      <div>
        <span title="文章發布於：{{ $postCreatedAtDateString }}">
          <i class="bi bi-clock-fill"></i><span class="ml-2">{{ $postCreatedAtDiffForHuman }}</span>
        </span>
      </div>
      <div>&bull;</div>
      <div>
        {{-- 文章留言數 --}}
        <span>
          <i class="bi bi-chat-square-text-fill"></i><span class="ml-2">{{ $postCommentCounts }}</span>
        </span>
      </div>
    </div>
  </div>

  <div class="flex items-center mt-2 md:mt-0">
    {{-- 還原文章 --}}
    <button
      onclick="confirm('你確定要還原該文章？') || event.stopImmediatePropagation()"
      wire:click="restore({{ $postId }})"
      type="button"
      class="inline-flex items-center justify-center w-10 h-10 transition duration-150 ease-in-out bg-blue-500 border border-transparent rounded-md text-gray-50 hover:bg-blue-600 active:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring ring-blue-300"
    >
      <i class="bi bi-arrow-counterclockwise"></i>
    </button>
  </div>

</x-card>
