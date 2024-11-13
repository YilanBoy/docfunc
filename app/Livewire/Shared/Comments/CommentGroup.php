<?php

namespace App\Livewire\Shared\Comments;

use App\Models\Comment;
use App\Models\Post;
use App\Traits\MarkdownConverter;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use League\CommonMark\Exception\CommonMarkException;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

class CommentGroup extends Component
{
    use AuthorizesRequests;
    use MarkdownConverter;

    #[Locked]
    public int $postId;

    #[Locked]
    public int $postUserId;

    #[Locked]
    public int $maxLayer = 2;

    #[Locked]
    public int $currentLayer = 1;

    #[Locked]
    public ?int $parentId = null;

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
     *  Comments array, the format is like:
     *
     * @var array<int, array{
     *     'id': int,
     *     'user_id': int,
     *     'body': string,
     *     'created_at': string,
     *     'updated_at': string,
     *     'children_count': int,
     *     'user': array{'id': int, 'name': string, 'gravatar_url': string}|null,
     *     'converted_body': string
     * }>
     */
    #[Locked]
    public array $comments = [];

    #[On('create-new-comment-to-{commentGroupName}')]
    public function createComment(array $comment): void
    {
        $this->comments = [$comment['id'] => $comment] + $this->comments;
    }

    /**
     * @throws CommonMarkException
     */
    #[On('update-comment-in-{commentGroupName}')]
    public function updateComment(int $id, string $body, string $updatedAt): void
    {
        $this->comments[$id]['body'] = $body;
        $this->comments[$id]['converted_body'] = $this->convertToHtml($body);
        $this->comments[$id]['updated_at'] = $updatedAt;
    }

    public function destroyComment(int $id): void
    {
        $comment = Comment::find(id: $id, columns: ['id', 'user_id', 'post_id']);

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

        unset($this->comments[$id]);

        $this->dispatch(event: 'update-comment-counts');

        $this->dispatch(event: 'info-badge', status: 'success', message: '成功刪除留言！');
    }

    public function render(): View
    {
        return view('livewire.shared.comments.comment-group');
    }
}
