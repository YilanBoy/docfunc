{{-- 會員留言 --}}
<div
  x-data="cardLink"
  class="space-y-6"
>
  @forelse ($comments as $comment)
    <x-card
      x-on:click="directToCardLink($event, $refs)"
      class="flex flex-col space-y-4 justify-between cursor-pointer group md:flex-row md:space-y-0"
    >
      {{-- 留言相關資訊 --}}
      {{-- 文章標題 --}}
      <span class="text-xl font-semibold dark:text-gray-50">
        <a
          x-ref="cardLinkUrl"
          href="{{ $comment->post->link_with_slug }}#comments"
          class="group-gradient-underline-grow"
        >
          {{ $comment->post->title }}
        </a>
      </span>

      <span class="text-slate-400">
        <i class="bi bi-clock-fill"></i>
        <span class="ml-2">{{ $comment->created_at->diffForHumans() }}</span>
      </span>
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
