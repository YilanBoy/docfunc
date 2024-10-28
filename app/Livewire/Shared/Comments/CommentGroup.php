<?php

namespace App\Livewire\Shared\Comments;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Builder;
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
     * - 'root-new-group' for the new comment with no parent id (top layer).
     * - '[command id]-new-group' for new comment with parent id (second layer or more).
     * - '[commend id]-group' for normal comment group.
     */
    public string $commentGroupName;

    /**
     * Comment ids.
     *
     * @var array<int>
     */
    public array $ids = [];

    #[On('refresh-{commentGroupName}')]
    public function appendCommentIdInGroup(int $id): void
    {
        $this->ids[] = $id;
    }

    /**
     * Delete the comment in group.
     *
     * @throws AuthorizationException
     * @throws Throwable
     */
    public function destroy(Comment $comment): void
    {
        $this->authorize('destroy', $comment);

        unset($this->ids[array_search($comment->id, $this->ids)]);

        DB::transaction(function () use ($comment) {
            $comment->delete();

            $post = Post::findOrFail($this->postId);

            $post->decrement('comment_counts');
        });

        $this->dispatch('update-comment-counts');

        $this->dispatch('info-badge', status: 'success', message: '成功刪除留言！');
    }

    public function render(): View
    {
        $comments = collect();

        if (count($this->ids) > 0) {
            $comments = Comment::query()
                ->select(['id', 'body', 'user_id', 'created_at', 'updated_at'])
                ->where('post_id', $this->postId)
                ->when(! is_null($this->parentId), function (Builder $query) {
                    $query->where('parent_id', $this->parentId);
                })
                ->whereIn('id', $this->ids)
                ->with('user:id,name,email')
                ->with('children')
                ->oldest('id')
                ->get();
        }

        return view('livewire.shared.comments.comment-group', compact('comments'));
    }
}
