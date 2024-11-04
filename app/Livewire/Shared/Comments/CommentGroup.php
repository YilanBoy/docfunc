<?php

namespace App\Livewire\Shared\Comments;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;
use Throwable;

class CommentGroup extends Component
{
    use AuthorizesRequests;

    #[Locked]
    public int $maxLayer = 2;

    #[Locked]
    public int $currentLayer = 1;

    #[Locked]
    public ?int $parentId = null;

    #[Locked]
    public int $postId;

    #[Locked]
    public int $postAuthorId;

    /**
     * Use this comment group name as a dynamic event name.
     * Other components can use this name to refresh specific comment group.
     *
     * There are three types of comment group names:
     *
     * - 'root-new-comment-group' for the new comment with no parent id (top layer).
     * - '[command id]-new-comment-group' for new comment with parent id (second layer or more).
     * - '[commend id]-comment-group' for normal comment group.
     */
    #[Locked]
    public string $commentGroupName;

    /**
     * Comments array, the format is like:
     * [
     *     1 => ["id" => 1, "body" => "hello" ...],
     *     2 => ["id" => 2, "body" => "world" ...],
     * ],
     */
    public array $comments = [];

    #[On('insert-new-comment-to-{commentGroupName}')]
    public function insertComment(array $comment): void
    {
        $this->comments = [$comment['id'] => $comment] + $this->comments;
    }

    /**
     * Delete the comment in group.
     *
     * @throws AuthorizationException
     * @throws Throwable
     */
    public function destroy(int $commentId): void
    {
        $comment = Comment::find(id: $commentId, columns: ['id', 'user_id', 'post_id']);

        // Check comment is not deleted
        if (is_null($comment)) {
            $this->dispatch(event: 'info-badge', status: 'danger', message: '該留言已被刪除！');

            return;
        }

        $this->authorize('destroy', $comment);

        $post = Post::findOrFail($this->postId);

        DB::transaction(function () use ($comment, $post) {
            $comment->delete();

            $post->decrement('comment_counts');
        });

        unset($this->comments[$commentId]);

        $this->dispatch('update-comment-counts');

        $this->dispatch('info-badge', status: 'success', message: '成功刪除留言！');
    }

    public function render(): View
    {
        return view('livewire.shared.comments.comment-group');
    }
}
