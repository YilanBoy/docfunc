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
    public int $postId;

    #[Locked]
    public int $postAuthorId;

    // Default group 'new' is for the new comments which will have its own group
    public int|string $groupId = 'new';

    /**
     * Comment ids.
     *
     * @var array<int>
     */
    public array $ids = [];

    #[On('create-comment-in-group-{groupId}')]
    public function create(int $id): void
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
                ->whereIn('id', $this->ids)
                ->with('user:id,name,email')
                ->latest('id')
                ->get();
        }

        return view('livewire.shared.comments.comment-group', compact('comments'));
    }
}
