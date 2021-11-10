<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Comment;

class CommentsGroup extends Component
{
    use AuthorizesRequests;

    public $post;
    public $offset;
    public $perPage;

    protected $listeners = ['refresh'];

    public function refresh()
    {
        // Refresh comments
    }

    // 刪除留言
    public function destroy(Comment $comment)
    {
        $this->authorize('destroy', $comment);

        $comment->delete();

        $this->post->updateCommentCount();
        $this->emit('updateCommentCount');
    }

    public function render()
    {
        $comments = $this->post->comments()
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
