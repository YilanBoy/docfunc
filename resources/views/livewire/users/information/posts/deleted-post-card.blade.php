<x-card class="group flex flex-col justify-between border-2 border-red-400 md:flex-row">
  {{-- 文章 --}}
  <div class="flex w-full flex-col justify-between">
    <span class="text-red-400">文章將於{{ $postWillDeletedAtDiffForHuman }}刪除</span>

    {{-- 文章標題 --}}
    <span class="mt-2 text-xl font-semibold dark:text-gray-50 md:mt-0">
      <span>{{ $postTitle }}</span>
    </span>

    {{-- 文章相關資訊 --}}
    <div class="mt-2 flex items-center space-x-2 text-base text-neutral-400">
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

  <div class="mt-2 flex items-center md:mt-0">
    {{-- 還原文章 --}}
    <button
      class="inline-flex h-10 w-10 items-center justify-center rounded-md border border-transparent bg-blue-500 text-gray-50 ring-blue-300 transition duration-150 ease-in-out hover:bg-blue-600 focus:border-blue-700 focus:outline-none focus:ring active:bg-blue-700"
      type="button"
      onclick="confirm('你確定要還原該文章？') || event.stopImmediatePropagation()"
      wire:click="restore({{ $postId }})"
    >
      <i class="bi bi-arrow-counterclockwise"></i>
    </button>
  </div>

</x-card>
