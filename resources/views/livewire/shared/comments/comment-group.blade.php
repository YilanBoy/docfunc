<div class="w-full">
  @foreach ($comments as $comment)
    <livewire:shared.comments.comment-card
      :$postId
      :$postAuthorId
      :comment-id="$comment->id"
      :user-id="$comment->user_id ?? 0"
      :user-gravatar-url="$comment->user ? get_gravatar($comment->user->email) : ''"
      :user-name="$comment->user->name ?? ''"
      :body="$comment->body"
      :created-at="$comment->created_at"
      :is-edited="$comment->created_at->ne($comment->updated_at)"
      {{-- when the parent component is updated, the child component is updated together --}}
      {{-- reference: https://github.com/livewire/livewire/discussions/1895 --}}
      :key="'comment-' . $comment->id"
    />
  @endforeach
</div>
