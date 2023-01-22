<div class="mt-6 space-y-6">
  @foreach ($comments as $comment)
    <livewire:comments.comment
      :post-id="$postId"
      :comment-id="$comment->id"
      :user-id="$comment->user_id"
      :user-gravatar-url="get_gravatar($comment->user_email)"
      :user-name="$comment->user_name"
      :body="$comment->body"
      :created-at="$comment->created_at->format('Y 年 m 月 d 日')"
      :post-user-id="$comment->post_user_id"
      {{-- when the parent component is updated, the child component is updated together --}}
      {{-- reference: https://github.com/livewire/livewire/discussions/1895 --}}
      :wire:key="now()->toString()"
    />
  @endforeach
</div>
