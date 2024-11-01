@script
  <script>
    Alpine.data('commentGroup', () => ({
      observers: [],
      highlightCodeInCommentCard() {
        this.$root
          .querySelectorAll('pre code:not(.hljs)')
          .forEach((element) => {
            hljs.highlightElement(element);
          });
      },
      init() {
        let commentsObserver = new MutationObserver(() => {
          this.highlightCodeInCommentCard();
        });

        commentsObserver.observe(this.$root, {
          childList: true,
          subtree: true,
          attributes: true,
        });

        this.observers.push(commentsObserver);

        this.highlightCodeInCommentCard();
      },
      destroy() {
        this.observers.forEach((observer) => {
          observer.disconnect();
        })
      }
    }));
  </script>
@endscript

<div
  class="w-full"
  x-data="commentGroup"
>
  @foreach ($this->comments as $comment)
    <livewire:shared.comments.comment-card
      :$maxLayer
      :$currentLayer
      :$postId
      :$postAuthorId
      :comment-id="$comment->id"
      :user-id="$comment->user_id ?? 0"
      :user-gravatar-url="$comment->user ? get_gravatar($comment->user->email) : ''"
      :user-name="$comment->user->name ?? ''"
      :body="$comment->body"
      :created-at="$comment->created_at"
      :is-edited="$comment->created_at->ne($comment->updated_at)"
      :has-children="$comment->children->count() > 0"
      {{-- when the parent component is updated, the child component is updated together --}}
      {{-- reference: https://github.com/livewire/livewire/discussions/1895 --}}
      :key="'comment-' . $comment->id . '-has-' . $comment->children->count() . '-children'"
    />
  @endforeach
</div>
