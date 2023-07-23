<x-card
  class="group flex cursor-pointer flex-col justify-between md:flex-row"
  x-data="cardLink"
  x-on:click="directToCardLink($event, $refs)"
>
  {{-- 文章 --}}
  <div class="flex w-full flex-col justify-between">
    {{-- 文章標題 --}}
    <span class="mt-2 text-xl font-semibold dark:text-gray-50 md:mt-0">
      <a
        class="group-gradient-underline-grow"
        href="{{ $postLink }}"
        x-ref="cardLinkUrl"
      >{{ $postTitle }}</a>
    </span>

    {{-- 文章相關資訊 --}}
    <div class="mt-2 flex items-center space-x-2 text-base text-neutral-400">
      {{-- 文章分類資訊 --}}
      <div>
        <a
          class="hover:text-neutral-500 dark:hover:text-neutral-300"
          href="{{ $categoryLink }}"
          title="{{ $categoryName }}"
        >
          <i class="{{ $categoryIcon }}"></i><span class="ml-2">{{ $categoryName }}</span>
        </a>
      </div>
      <div>&bull;</div>
      {{-- 文章發布時間 --}}
      <div>
        <a
          class="hover:text-neutral-500 dark:hover:text-neutral-300"
          href="{{ $postLink }}"
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
    <div class="mt-2 flex items-center space-x-2 md:mt-0">
      {{-- 編輯文章 --}}
      <a
        class="inline-flex h-10 w-10 items-center justify-center rounded-md border border-transparent bg-green-500 text-gray-50 ring-green-300 transition duration-150 ease-in-out hover:bg-green-600 focus:border-green-700 focus:outline-none focus:ring active:bg-green-700"
        href="{{ route('posts.edit', ['id' => $postId]) }}"
      >
        <i class="bi bi-pencil-square"></i>
      </a>

      {{-- 刪除 --}}
      <button
        class="inline-flex h-10 w-10 items-center justify-center rounded-md border border-transparent bg-red-500 text-gray-50 ring-red-300 transition duration-150 ease-in-out hover:bg-red-600 focus:border-red-700 focus:outline-none focus:ring active:bg-red-700"
        type="button"
        onclick="confirm('你確定要刪除文章嗎？（7 天之內可以還原）') || event.stopImmediatePropagation()"
        wire:click.stop="deletePost({{ $postId }})"
      >
        <i class="bi bi-trash-fill"></i>
      </button>
    </div>
  @endif
</x-card>
