<?php

namespace App\Http\Livewire\Comments;

use App\Http\Traits\Livewire\MarkdownConverter;
use App\Models\Comment as CommentModel;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Comment extends Component
{
    use AuthorizesRequests;
    use MarkdownConverter;

    public int $postId;

    public int $commentId;

    public int $userId;

    public string $userGravatarUrl;

    public string $userName;

    public string $body;

    public string $createdAt;

    public string $postUserId;

    public string $offset;

    // comment group id is used to refresh comment group component
    public int $groupId;

    public function getConvertedBodyProperty(): string
    {
        return $this->convertToMarkdown($this->body);
    }

    // 刪除留言
    public function destroy(CommentModel $comment)
    {
        $this->authorize('destroy', $comment);

        $comment->delete();

        $this->emit('updateCommentCounts');

        $this->emit('refreshAllCommentGroup');
    }

    public function render()
    {
        return view('livewire.comments.comment');
    }
}
