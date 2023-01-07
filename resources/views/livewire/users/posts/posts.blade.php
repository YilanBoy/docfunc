{{-- 會員文章 --}}
<div class="space-y-6">
  {{-- 文章列表 --}}
  @forelse ($posts as $post)

    @if (!$post->trashed())
      <livewire:users.posts.post-card
        :postId="$post->id"
        :postTitle="$post->title"
        :postLink="$post->link_with_slug"
        :postAuthorId="$post->user_id"
        :postCreatedAtDateString="$post->created_at->toDateString()"
        :postCreatedAtDiffForHuman="$post->created_at->diffForHumans()"
        :postCommentCount="$post->comment_count"
        :categoryLink="$post->category->link_with_name"
        :categoryName="$post->category->name"
        :categoryIcon="$post->category->icon"
        wire:key="post-{{ $post->id }}"
      />
    @endif

    @if ($post->trashed() && auth()->id() === $post->user_id)
      <livewire:users.posts.deleted-post-card
        :post-id="$post->id"
        :post-title="$post->title"
        :post-created-at-date-string="$post->created_at->toDateString()"
        :post-created-at-diff-for-human="$post->created_at->diffForHumans()"
        :post-will-deleted-at-diff-for-human="$post->deleted_at->addDays(7)->diffForHumans()"
        :post-comment-count="$post->comment_count"
        :category-name="$post->category->name"
        :category-icon="$post->category->icon"
        wire:key="deleted-post-{{ $post->id }}"
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
