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
    public int $authorId;
    public int $offset;
    public int $perPage;

    protected $listeners = ['refreshCommentsGroup' => '$refresh'];

    // 刪除留言
    public function destroy(Comment $comment)
    {
        $this->authorize('destroy', $comment);

        $comment->delete();

        Post::find($this->postId)->updateCommentCount();
        $this->emit('updateCommentCount');
    }

    public function render()
    {
        $comments = Post::find($this->postId)->comments()
            // 不撈取子留言
            ->whereNull('parent_id')
            ->latest()
            ->limit($this->perPage)
            ->offset($this->offset)
            ->with(['children' => function ($query) {
                $query->oldest();
            }])
            ->get();

        return view('livewire.comments-group', ['comments' => $comments]);
    }
}
