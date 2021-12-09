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
            ->select('comments.*', 'posts.user_id as post_user_id')
            ->join('posts', 'posts.id', '=', 'comments.post_id')
            ->whereNull('parent_id')
            ->latest()
            ->limit($this->perPage)
            ->offset($this->offset)
            ->with(['subComments' => function ($query) {
                $query->oldest();
            }])
            ->get();

        return view('livewire.comments-group', ['comments' => $comments]);
    }
}
