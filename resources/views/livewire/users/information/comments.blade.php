{{-- 會員留言 --}}
<div class="space-y-6">
  @forelse ($comments as $comment)
    <x-card
      x-data="cardLink"
      x-on:click="directToCardLink($event, $refs)"
      class="flex flex-col justify-between cursor-pointer group md:flex-row"
    >
      {{-- 留言相關資訊 --}}
      <div class="flex justify-between w-full">
        {{-- 文章標題 --}}
        <div class="flex flex-col justify-between">
          <span class="text-xl font-semibold dark:text-gray-50">
            <a
              x-ref="cardLinkUrl"
              href="{{ $comment->post->link_with_slug }}#comments"
              class="group-gradient-underline-grow"
            >
              {{ $comment->post->title }}
            </a>
          </span>

          <p class="mt-2 text-gray-400 whitespace-pre-wrap text-base">{{ $comment->body }}</p>

          <span class="mt-2 text-slate-400 xl:hidden">
            <i class="bi bi-clock-fill"></i><span class="ml-2">{{ $comment->created_at->diffForHumans() }}</span>
          </span>
        </div>

        {{-- 文章發布時間 --}}
        <span class="items-center justify-center hidden text-neutral-400 xl:flex text-base">
          <i class="bi bi-clock-fill"></i><span class="ml-2">{{ $comment->created_at->diffForHumans() }}</span>
        </span>

      </div>
    </x-card>

  @empty
    <x-card class="flex items-center justify-center w-full h-36 dark:text-gray-50">
      <span>目前沒有留言，快點找文章進行留言吧！</span>
    </x-card>
  @endforelse

  <div>
    {{ $comments->onEachSide(1)->links() }}
  </div>
</div>
