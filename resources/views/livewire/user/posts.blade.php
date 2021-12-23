{{-- 會員文章 --}}
<div class="space-y-6">
  {{-- 文章列表 --}}
  @forelse ($posts as $post)
    <x-card
      x-data="cardLink"
      x-on:click="directToCardLink($event, $refs)"
      class="flex flex-col justify-between cursor-pointer group md:flex-row"
    >
      {{-- 文章 --}}
      <div class="flex flex-col justify-between w-full">
        @if ($post->trashed())
          <span class="text-red-400">此文章已被標記為刪除狀態！</span>
        @endif

        {{-- 文章標題 --}}
        <span class="mt-2 text-xl font-semibold md:mt-0 dark:text-gray-50">
          <a
            x-ref="cardLinkUrl"
            href="{{ $post->trashed() ? '#' : $post->link_with_slug }}"
            @class([
              '',
              'group-gradient-underline-grow' => !$post->trashed(),
            ])
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
              href="{{ $post->trashed() ? '#' : $post->link_with_slug }}"
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
              href="{{ $post->trashed() ? '#' : $post->link_with_slug . '#post-' . $post->id . '-comments' }}"
            >
              <i class="bi bi-chat-square-text-fill"></i><span class="ml-2">{{ $post->comment_count }}</span>
            </a>
          </div>
        </div>
      </div>

      @if (auth()->id() === $post->user_id)
        @if ($post->trashed())
          {{-- 還原文章隱藏表單 --}}
          <form
            id="restore-post-{{ $post->id }}"
            action="{{ route('posts.restore', ['id' => $post->id]) }}"
            method="POST"
            class="hidden"
          >
            @csrf
          </form>

          {{-- 完全刪除隱藏表單 --}}
          <form
            id="destroy-post-{{ $post->id }}"
            action="{{ route('posts.destroy', ['id' => $post->id]) }}"
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
                  document.getElementById('restore-post-{{ $post->id }}').submit()
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
                  document.getElementById('destroy-post-{{ $post->id }}').submit()
                }
              "
              type="button"
              class="inline-flex items-center justify-center w-10 h-10 transition duration-150 ease-in-out bg-red-500 border border-transparent rounded-md text-gray-50 hover:bg-red-600 active:bg-red-700 focus:outline-none focus:border-red-700 focus:ring ring-red-300"
            >
              <i class="bi bi-trash-fill"></i>
            </button>
          </div>
        @else
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
      @endif
    </x-card>

  @empty
    <x-card class="flex items-center justify-center w-full h-36 dark:text-gray-50">
      <span>目前沒有文章，有沒有什麼事情想要分享呢？</span>
    </x-card>
  @endforelse

  <div>
    {{ $posts->onEachSide(1)->links() }}
  </div>
</div>
