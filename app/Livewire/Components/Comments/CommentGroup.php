<?php

namespace App\Livewire\Components\Comments;

use App\Models\Comment;
use Livewire\Attributes\Locked;
use Livewire\Component;

class CommentGroup extends Component
{
    #[Locked]
    public int $postId;

    public int $perPage;

    public int $offset;

    protected $listeners = [
        'refreshComments' => '$refresh',
    ];

    public function render()
    {
        $comments = Comment::query()
            ->selectRaw('
                comments.id,
                comments.body,
                comments.user_id,
                comments.created_at,
                comments.updated_at,
                posts.user_id AS post_user_id,
                users.name AS user_name,
                users.email AS user_email
            ')
            ->join('posts', 'posts.id', '=', 'comments.post_id')
            ->leftJoin('users', 'users.id', '=', 'comments.user_id')
            ->where('post_id', $this->postId)
            ->latest('comments.id')
            ->limit($this->perPage)
            ->offset($this->offset)
            ->get();

        return view('livewire.components.comments.comment-group', ['comments' => $comments]);
    }
}
