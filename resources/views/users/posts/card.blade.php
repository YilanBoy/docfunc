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
        href="{{ $post->link_with_slug }}"
        class="group-gradient-underline-grow"
      >{{ $post->title }}</a>
    </span>

    {{-- 文章相關資訊 --}}
    <div class="flex items-center mt-2 space-x-2 text-sm text-gray-400">
      {{-- 文章分類資訊 --}}
      <div>
        <a
          href="{{ $post->category->link_with_name }}"
          title="{{ $post->category->name }}"
          class="hover:text-gray-700 dark:hover:text-gray-50"
        >
          <i class="{{ $post->category->icon }}"></i><span class="ml-2">{{ $post->category->name }}</span>
        </a>
      </div>
      <div>&bull;</div>
      {{-- 文章發布時間 --}}
      <div>
        <a
          href="{{ $post->link_with_slug }}"
          class="hover:text-gray-700 dark:hover:text-gray-50"
          title="文章發布於：{{ $post->created_at }}"
        >
          <i class="bi bi-clock-fill"></i><span class="ml-2">{{ $post->created_at->diffForHumans() }}</span>
        </a>
      </div>
      <div>&bull;</div>
      <div>
        {{-- 文章留言數 --}}
        <a
          class="hover:text-gray-700 dark:hover:text-gray-50"
          href="{{ $post->link_with_slug . '#post-' . $post->id . '-comments' }}"
        >
          <i class="bi bi-chat-square-text-fill"></i><span class="ml-2">{{ $post->comment_count }}</span>
        </a>
      </div>
    </div>
  </div>

  @if (auth()->id() === $post->user_id)
    {{-- 軟刪除隱藏表單 --}}
    <form
      id="soft-delete-post-{{ $post->id }}"
      action="{{ route('posts.softDelete', ['post' => $post->id]) }}"
      method="POST"
      class="hidden"
    >
      @csrf
      @method('DELETE')
    </form>

    <div class="flex items-center mt-2 space-x-2 md:mt-0">
      {{-- 編輯文章 --}}
      <a
        href="{{ route('posts.edit', ['post' => $post->id]) }}"
        class="inline-flex items-center justify-center w-10 h-10 transition duration-150 ease-in-out bg-green-500 border border-transparent rounded-md text-gray-50 hover:bg-green-600 active:bg-green-700 focus:outline-none focus:border-green-700 focus:ring ring-green-300"
      >
        <i class="bi bi-pencil-fill"></i>
      </a>

      {{-- 軟刪除 --}}
      <button
        x-on:click.prevent.stop="
          if (confirm('您確定標記此文章為刪除狀態嗎？（30 天內還可以恢復）'))
          {
            document.getElementById('soft-delete-post-{{ $post->id }}').submit()
          }
        "
        type="button"
        class="inline-flex items-center justify-center w-10 h-10 transition duration-150 ease-in-out bg-orange-500 border border-transparent rounded-md text-gray-50 hover:bg-orange-600 active:bg-orange-700 focus:outline-none focus:border-orange-700 focus:ring ring-orange-300"
      >
        <i class="bi bi-file-earmark-x-fill"></i>
      </button>
    </div>
  @endif
</x-card>
