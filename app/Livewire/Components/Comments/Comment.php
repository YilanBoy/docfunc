<?php

namespace App\Livewire\Components\Comments;

use App\Livewire\Traits\MarkdownConverter;
use App\Models\Comment as CommentModel;
use App\Models\Post;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use League\CommonMark\Exception\CommonMarkException;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;
use Throwable;

class Comment extends Component
{
    use AuthorizesRequests;
    use MarkdownConverter;

    #[Locked]
    public int $postId;

    #[Locked]
    public int $commentId;

    #[Locked]
    public int $userId;

    #[Locked]
    public string $postUserId;

    public string $userGravatarUrl;

    public string $userName;

    public string $body;

    public string $createdAt;

    public string $isEdited;

    /**
     * @throws CommonMarkException
     */
    public function getConvertedBodyProperty(): string
    {
        return $this->convertToHtml($this->body);
    }

    #[On('comment-updated.{commentId}')]
    public function refreshComment(): void
    {
        $comment = CommentModel::findOrFail($this->commentId);
        $this->body = $comment->body;
        $this->createdAt = $comment->created_at->diffForHumans();
        $this->isEdited = true;
    }

    /**
     * delete comment
     *
     * @throws AuthorizationException
     * @throws Throwable
     */
    public function destroy(CommentModel $comment): void
    {
        $this->authorize('destroy', $comment);

        DB::transaction(function () use ($comment) {
            $comment->delete();

            $post = Post::findOrFail($this->postId);

            $post->decrement('comment_counts');
        });

        $this->dispatch('updateCommentCounts');

        $this->dispatch('refreshComments');
    }

    public function render()
    {
        return view('livewire.components.comments.comment');
    }
}
