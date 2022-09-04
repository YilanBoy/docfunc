{{-- 會員文章 --}}
<div class="space-y-6">
  {{-- 文章列表 --}}
  @forelse ($posts as $post)

    {{--    @includeWhen(!$post->trashed(), 'users.posts.card')--}}
    @if (!$post->trashed())
      <livewire:users.posts.post-card
        :category-link="$post->category->link_with_name"
        :category-name="$post->category->name"
        :category-icon="$post->category->icon"
        :post-id="$post->id"
        :link="$post->link_with_slug"
        :title="$post->title"
        :created-at="$post->created_at"
        :created-at-to-date-string="$post->created_at->toDateString()"
        :comment-count="$post->comment_count"
        :author-id="$post->user_id"
        wire:key="post-{{ $post->id }}"
      />
    @endif

    @if ($post->trashed() && auth()->id() === $post->user_id)
      <livewire:users.posts.deleted-post-card
        :category-link="$post->category->link_with_name"
        :category-name="$post->category->name"
        :category-icon="$post->category->icon"
        :post-id="$post->id"
        :link="$post->link_with_slug"
        :title="$post->title"
        :created-at="$post->created_at"
        :created-at-to-date-string="$post->created_at->toDateString()"
        :comment-count="$post->comment_count"
        :destroy-date="$post->deleted_at->addDays(30)->diffForHumans()"
        wire:key="post-{{ $post->id }}"
      />
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
