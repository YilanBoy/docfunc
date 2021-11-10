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
    public $count = 10;
    public $perPage = 10;
    public $showMoreButtonIsActive = true;

    public function showMore()
    {
        $this->count += $this->perPage;

        if ($this->count >= $this->post->comments()->whereNull('parent_id')->count()) {
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
