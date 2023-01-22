<?php

namespace App\Http\Livewire\Comments;

use App\Models\Comment as CommentModel;
use App\Models\Post;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Str;

class Comment extends Component
{
    use AuthorizesRequests;

    public int $postId;

    public int $commentId;

    public int $userId;

    public string $userGravatarUrl;

    public string $userName;

    public string $body;

    public string $createdAt;

    public string $postUserId;

    public function getConvertedBodyProperty(): string
    {
        return Str::of($this->body)->markdown([
            'html_input' => 'strip',
        ]);
    }

    // 刪除留言
    public function destroy(CommentModel $comment)
    {
        $this->authorize('destroy', $comment);

        $comment->delete();

        Post::findOrFail($this->postId)->decrementCommentCount();

        $this->emit('updateCommentCount');

        $this->emit('refreshCommentGroup');
    }

    public function render()
    {
        return view('livewire.comments.comment');
    }
}
