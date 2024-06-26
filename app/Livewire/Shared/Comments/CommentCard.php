<?php

namespace App\Livewire\Shared\Comments;

use App\Models\Comment;
use App\Traits\MarkdownConverter;
use Illuminate\View\View;
use League\CommonMark\Exception\CommonMarkException;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

/**
 * @property string $convertedBody 將 markdown 的 body 轉換成 html 格式，set by convertedBody()
 */
class CommentCard extends Component
{
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

    public bool $isEdited;

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

    public function render(): View
    {
        return view('livewire.shared.comments.comment-card');
    }
}
