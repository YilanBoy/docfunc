<?php

namespace App\Http\Livewire\Components\Comments;

use App\Http\Traits\Livewire\MarkdownConverter;
use App\Models\Comment as CommentModel;
use App\Models\Post;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Throwable;

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

    public string $isEdited;

    public string $postUserId;

    public string $offset;

    public function getConvertedBodyProperty(): string
    {
        return $this->convertToHtml($this->body);
    }

    /**
     * delete comment
     *
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

        $this->emit('updateCommentCounts');

        $this->emit('refreshAllComments');
    }

    public function render()
    {
        return view('livewire.components.comments.comment');
    }
}
