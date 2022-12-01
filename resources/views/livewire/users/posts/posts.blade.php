{{-- 會員文章 --}}
<div class="space-y-6">
  {{-- 文章列表 --}}
  @forelse ($posts as $post)

    {{--    @includeWhen(!$post->trashed(), 'users.posts.card')--}}
    @if (!$post->trashed())
      <livewire:users.posts.post-card :post="$post" wire:key="post-{{ $post->id }}"/>
    @endif

    @if ($post->trashed() && auth()->id() === $post->user_id)
      <livewire:users.posts.deleted-post-card :post="$post" wire:key="deleted-post-{{ $post->id }}"/>
    @endif

  @empty
    <x-card class="flex items-center justify-center w-full h-36 dark:text-gray-50">
      <span>目前沒有文章，有沒有什麼事情想要分享呢？</span>
    </x-card>
  @endforelse

  <div>
    {{ $posts->onEachSide(1)->links() }}
  </div>
</div>
