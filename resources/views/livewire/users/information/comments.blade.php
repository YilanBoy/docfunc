@push('css')
  {{-- highlight code block --}}
  @vite('node_modules/highlight.js/scss/base16/material-palenight.scss')
@endpush

@push('script')
  {{-- highlight code block --}}
  @vite('resources/ts/highlight.ts')
@endpush

{{-- 會員留言 --}}
<div
  class="space-y-6"
  x-data
  x-init="Livewire.hook('message.processed', (message) => {
      // when change page in comments tab, highlight code block
      if (message.updateQueue[0].method.toLowerCase().includes('page')) {
          window.hljs.highlightAll();
      }
  })"
>
  @forelse ($comments as $comment)
    <x-dashed-card
      class="group relative max-h-64 cursor-pointer overflow-hidden after:absolute after:inset-x-0 after:bottom-0 after:h-1/2 after:bg-gradient-to-b after:from-transparent after:to-gray-100 dark:after:to-gray-800"
      x-data="cardLink"
      x-on:click="directToCardLink($event, $refs)"
    >
      <a
        class="group-gradient-underline-grow text-xl font-semibold dark:text-gray-50"
        href="{{ $comment->post->link_with_slug }}#comments"
        x-ref="cardLinkUrl"
      >
        {{ $comment->post->title }}
      </a>

      <div class="flex flex-col">
        {{-- 留言 --}}
        <div class="comment-body">
          {!! $comment->body !!}
        </div>
      </div>

      <div
        class="absolute bottom-3 right-3 z-10 rounded-lg bg-emerald-600 px-2 py-1 text-sm text-gray-50 dark:bg-lividus-600"
      >
        <i class="bi bi-clock-fill"></i>
        <span class="ml-2">{{ $comment->created_at->diffForHumans() }}</span>
      </div>
    </x-dashed-card>

  @empty
    <x-card class="flex h-36 w-full items-center justify-center dark:text-gray-50">
      <span>目前沒有留言，快點找文章進行留言吧！</span>
    </x-card>
  @endforelse

  <div>
    {{ $comments->onEachSide(1)->links() }}
  </div>
</div>
