<?php

namespace App\Http\Livewire\Comments;

use App\Models\Comment;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class CommentGroup extends Component
{
    use AuthorizesRequests;

    public int $postId;

    public int $perPage;

    public int $offset;

    public int $groupId;

    protected function getListeners()
    {
        return [
            'refreshAllCommentGroup' => '$refresh',
            'refreshCommentGroup'.$this->groupId => '$refresh',
        ];
    }

    public function render()
    {
        $comments = Comment::query()
            ->selectRaw('
                comments.id,
                comments.body,
                comments.user_id,
                comments.created_at,
                posts.user_id AS post_user_id,
                users.name AS user_name,
                users.email AS user_email
            ')
            ->join('posts', 'posts.id', '=', 'comments.post_id')
            ->join('users', 'users.id', '=', 'comments.user_id')
            ->where('post_id', $this->postId)
            ->latest('id')
            ->limit($this->perPage)
            ->offset($this->offset)
            ->get();

        return view('livewire.comments.comment-group', ['comments' => $comments]);
    }
}
