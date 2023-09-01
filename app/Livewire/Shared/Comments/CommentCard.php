<?php

namespace App\Livewire\Shared\Comments;

use App\Livewire\Traits\MarkdownConverter;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use League\CommonMark\Exception\CommonMarkException;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;
use Throwable;

/**
 * @property string convertedBody 將 markdown 的 body 轉換成 html 格式，set by convertedBody()
 */
class CommentCard extends Component
{
    use AuthorizesRequests;
    use MarkdownConverter;

    #[Locked]
    public int $postId;

    #[Locked]
    public int $postAuthorId;

    #[Locked]
    public int $commentId;

    #[Locked]
    public int $userId;

    public string $userGravatarUrl;

    public string $userName;

    public string $body;

    public object $createdAt;

    public string $isEdited;

    public int|string $groupId;

    /**
     * @throws CommonMarkException
     */
    #[Computed]
    public function convertedBody(): string
    {
        return $this->convertToHtml($this->body);
    }

    #[On('comment-updated.{commentId}')]
    public function refreshComment(): void
    {
        $comment = Comment::findOrFail($this->commentId);

        $this->body = $comment->body;
        $this->createdAt = $comment->created_at;
        $this->isEdited = true;
    }

    /**
     * delete comment
     *
     * @throws AuthorizationException
     * @throws Throwable
     */
    public function destroy(): void
    {
        $comment = Comment::findOrFail($this->commentId);

        $this->authorize('destroy', $comment);

        DB::transaction(function () use ($comment) {
            $comment->delete();

            $post = Post::findOrFail($this->postId);

            $post->decrement('comment_counts');
        });

        $this->dispatch('remove-id-from-group-'.$this->groupId, id: $this->commentId);

        $this->dispatch('update-comment-counts');

        $this->dispatch('info-badge', status: 'success', message: '成功刪除留言！');
    }

    public function render()
    {
        return view('livewire.shared.comments.comment-card');
    }
}
