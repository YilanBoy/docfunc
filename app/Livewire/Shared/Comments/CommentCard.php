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
    public int $postUserId;

    #[Locked]
    public int $maxLayer;

    #[Locked]
    public int $currentLayer;

    #[Locked]
    public int $childrenPerPage = 3;

    #[Locked]
    public int $commentId;

    #[Locked]
    public ?int $commentUserId;

    #[Locked]
    public ?string $commentUserGravatarUrl;

    #[Locked]
    public string $commentUserName;

    #[Locked]
    public string $commentBody;

    #[Locked]
    public string $commentCreatedAt;

    #[Locked]
    public bool $commentIsEdited;

    #[Locked]
    public bool $commentHasChildren;

    /**
     * @throws CommonMarkException
     */
    #[Computed]
    public function convertedBody(): string
    {
        return $this->convertToHtml($this->commentBody);
    }

    #[On('update-comment-{commentId}')]
    public function refreshComment(): void
    {
        $comment = Comment::findOrFail($this->commentId);

        $this->commentBody = $comment->body;
        $this->commentCreatedAt = $comment->created_at;
        $this->commentIsEdited = true;
    }

    public function render(): View
    {
        return view('livewire.shared.comments.comment-card');
    }
}
