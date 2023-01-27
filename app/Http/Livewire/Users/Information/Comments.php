<?php

namespace App\Http\Livewire\Users\Information;

use App\Models\Comment;
use Livewire\Component;
use Livewire\WithPagination;

class Comments extends Component
{
    use WithPagination;

    public int $userId;

    public function dehydrate()
    {
        $this->dispatchBrowserEvent('scroll-to-top');
    }

    public function render()
    {
        // 該會員的留言
        $comments = Comment::whereUserId($this->userId)
            ->select(['created_at', 'post_id'])
            ->whereHas('post', function ($query) {
                $query->whereNull('deleted_at');
            })
            ->with('post:id,title,slug')
            ->latest()
            ->paginate(10, ['*'], 'commentsPage')
            ->withQueryString();

        return view('livewire.users.information.comments', ['comments' => $comments]);
    }
}
