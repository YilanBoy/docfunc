<div class="mt-6 space-y-6">
  @foreach ($comments as $comment)
    <livewire:components.comments.comment
      :post-id="$postId"
      :comment-id="$comment->id"
      :user-id="$comment->user_id ?? 0"
      :user-gravatar-url="$comment->user_email ? get_gravatar($comment->user_email) : ''"
      :user-name="$comment->user_name ?? ''"
      :body="$comment->body"
      :created-at="$comment->created_at->format('Y 年 m 月 d 日')"
      :is-edited="$comment->created_at->ne($comment->updated_at)"
      :post-user-id="$comment->post_user_id"
      :offset="$offset"
      {{-- when the parent component is updated, the child component is updated together --}}
      {{-- reference: https://github.com/livewire/livewire/discussions/1895 --}}
      :wire:key="'comment-'.$comment->id.'-'.md5($comment->body)"
    />
  @endforeach
</div>
