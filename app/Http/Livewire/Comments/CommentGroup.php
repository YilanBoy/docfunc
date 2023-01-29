<?php

namespace App\Http\Livewire\Comments;

use App\Models\Comment;
use Livewire\Component;

class CommentGroup extends Component
{
    public int $postId;

    public int $perPage;

    public int $offset;

    /**
     * comment group id
     *
     * @var int
     */
    public int $groupId;

    protected function getListeners(): array
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
            ->leftJoin('users', 'users.id', '=', 'comments.user_id')
            ->where('post_id', $this->postId)
            ->latest('id')
            ->limit($this->perPage)
            ->offset($this->offset)
            ->get();

        return view('livewire.comments.comment-group', ['comments' => $comments]);
    }
}
