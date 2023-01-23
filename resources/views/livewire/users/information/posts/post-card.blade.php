<x-card
  x-data="cardLink"
  x-on:click="directToCardLink($event, $refs)"
  class="flex flex-col justify-between cursor-pointer group md:flex-row"
>
  {{-- 文章 --}}
  <div class="flex flex-col justify-between w-full">
    {{-- 文章標題 --}}
    <span class="mt-2 text-xl font-semibold md:mt-0 dark:text-gray-50">
      <a
        x-ref="cardLinkUrl"
        href="{{ $postLink }}"
        class="group-gradient-underline-grow"
      >{{ $postTitle }}</a>
    </span>

    {{-- 文章相關資訊 --}}
    <div class="flex items-center mt-2 space-x-2 text-base text-neutral-400">
      {{-- 文章分類資訊 --}}
      <div>
        <a
          href="{{ $categoryLink }}"
          title="{{ $categoryName }}"
          class="hover:text-neutral-500 dark:hover:text-neutral-300"
        >
          <i class="{{ $categoryIcon }}"></i><span class="ml-2">{{ $categoryName }}</span>
        </a>
      </div>
      <div>&bull;</div>
      {{-- 文章發布時間 --}}
      <div>
        <a
          href="{{ $postLink }}"
          class="hover:text-neutral-500 dark:hover:text-neutral-300"
          title="文章發布於：{{ $postCreatedAtDateString }}"
        >
          <i class="bi bi-clock-fill"></i><span class="ml-2">{{ $postCreatedAtDiffForHuman }}</span>
        </a>
      </div>
      <div>&bull;</div>
      <div>
        {{-- 文章留言數 --}}
        <a
          class="hover:text-neutral-500 dark:hover:text-neutral-300"
          href="{{ $postLink . '#post-' . $postId . '-comments' }}"
        >
          <i class="bi bi-chat-square-text-fill"></i><span class="ml-2">{{ $postCommentCounts }}</span>
        </a>
      </div>
    </div>
  </div>

  @if (auth()->id() === $postAuthorId)
    <div class="flex items-center mt-2 space-x-2 md:mt-0">
      {{-- 編輯文章 --}}
      <a
        href="{{ route('posts.edit', ['id' => $postId ]) }}"
        class="inline-flex items-center justify-center w-10 h-10 transition duration-150 ease-in-out bg-green-500 border border-transparent rounded-md text-gray-50 hover:bg-green-600 active:bg-green-700 focus:outline-none focus:border-green-700 focus:ring ring-green-300"
      >
        <i class="bi bi-pencil-square"></i>
      </a>

      {{-- 刪除 --}}
      <button
        onclick="confirm('你確定要刪除文章嗎？（7 天之內可以還原）') || event.stopImmediatePropagation()"
        wire:click.stop="deletePost({{ $postId }})"
        type="button"
        class="inline-flex items-center justify-center w-10 h-10 transition duration-150 ease-in-out bg-red-500 border border-transparent rounded-md text-gray-50 hover:bg-red-600 active:bg-red-700 focus:outline-none focus:border-red-700 focus:ring ring-red-300"
      >
        <i class="bi bi-trash-fill"></i>
      </button>
    </div>
  @endif
</x-card>
