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
  @foreach ($comments as $comment)
    <livewire:shared.comments.comment-card
      :post-id="$postId"
      :post-user-id="$postUserId"
      :max-layer="$maxLayer"
      :current-layer="$currentLayer"
      :comment-id="$comment['id']"
      :comment-user-id="is_null($comment['user']) ? null : $comment['user']['id']"
      :comment-user-gravatar-url="is_null($comment['user']) ? null : $comment['user']['gravatar_url']"
      :comment-user-name="is_null($comment['user']) ? '訪客' : $comment['user']['name']"
      :comment-body="$comment['body']"
      :comment-created-at="$comment['created_at']"
      :comment-is-edited="$comment['created_at'] !== $comment['updated_at']"
      :comment-has-children="$comment['children_count'] > 0"
      :key="'comment-card-' . $comment['id']"
    />
  @endforeach
</div>
