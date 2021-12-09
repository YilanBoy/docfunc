<?php

namespace App\Http\Livewire;

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
        $comments = Post::findOrFail($this->postId)
            ->comments()
            ->selectRaw('
                comments.*,
                posts.user_id AS post_user_id,
                users.name AS user_name,
                MD5(LOWER(TRIM(users.email))) AS user_email_hash
            ')
            ->join('posts', 'posts.id', '=', 'comments.post_id')
            ->join('users', 'users.id', '=', 'comments.user_id')
            ->whereNull('parent_id')
            ->with(['subComments' => function ($query) {
                $query->selectRaw('
                    comments.*,
                    users.name AS user_name,
                    MD5(LOWER(TRIM(users.email))) AS user_email_hash
                ')
                    ->join('users', 'users.id', '=', 'comments.user_id')
                    ->oldest();
            }])
            ->latest()
            ->limit($this->perPage)
            ->offset($this->offset)
            ->get();

        // dd($comments);

        return view('livewire.comments-group', ['comments' => $comments]);
    }
}
