<?php

namespace App\Http\Livewire\Comments;

use App\Models\Post;
use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Comment;

class CommentsGroup extends Component
{
    use AuthorizesRequests;

    public int $postId;
    public int $offset;
    public int $perPage;

    protected $listeners = ['refreshCommentsGroup' => '$refresh'];

    // 刪除留言
    public function destroy(Comment $comment)
    {
        $this->authorize('destroy', $comment);

        $comment->delete();

        Post::findOrFail($this->postId)->updateCommentCount();

        $this->emit('updateCommentCount');
    }

    public function render()
    {
        $comments = Comment::query()
            ->selectRaw('
                comments.*,
                posts.user_id AS post_user_id,
                users.name AS user_name,
                CONCAT(
                    "https://www.gravatar.com/avatar/",
                    MD5(LOWER(TRIM(users.email))),
                    "?s=400&d=mp"
                ) AS gravatar_url
            ')
            ->join('posts', 'posts.id', '=', 'comments.post_id')
            ->join('users', 'users.id', '=', 'comments.user_id')
            ->where('post_id', $this->postId)
            ->latest()
            ->limit($this->perPage)
            ->offset($this->offset)
            ->get();

        return view('livewire.comments.comments-group', ['comments' => $comments]);
    }
}
