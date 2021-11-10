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
    public $count = 3;
    public $perPage = 3;
    public $showMoreButtonIsActive = true;

    public function showMore()
    {
        $parentCommentCount = $this->post->comments()->whereNull('parent_id')->count();

        if ($this->count < $parentCommentCount) {
            $this->count += $this->perPage;
        } else {
            $this->showMoreButtonIsActive = false;
        }
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
        return view('livewire.comments');
    }
}
