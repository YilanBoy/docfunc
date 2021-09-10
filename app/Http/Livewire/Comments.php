<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Comment;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\WithPagination;

class Comments extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public $post;

    protected $listeners = ['refresh'];

    // 刪除留言
    public function destroy(Comment $comment)
    {
        $this->authorize('destroy', $comment);

        $comment->delete();

        $this->post->updateCommentCount();
        $this->emit('updateCommentCount');
    }

    public function refresh()
    {
        // Refresh comments
    }

    public function render()
    {
        $comments = $this->post->comments()
            // 不撈取子留言
            ->whereNull('parent_id')
            ->oldest()
            ->with(['children' => function ($query) {
                $query->oldest();
            }])
            ->paginate(10);

        return view('livewire.comments', ['comments' => $comments]);
    }
}
