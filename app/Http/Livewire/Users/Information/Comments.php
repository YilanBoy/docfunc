<?php

namespace App\Http\Livewire\Users\Information;

use App\Models\Comment;
use Livewire\Component;
use Livewire\WithPagination;

class Comments extends Component
{
    use WithPagination;

    public int $userId;

    public function hydrate()
    {
        $this->dispatchBrowserEvent('scroll-to-top');
    }

    public function render()
    {
        // 該會員的留言
        $comments = Comment::whereUserId($this->userId)
            ->whereHas('post', function ($query) {
                $query->whereNull('deleted_at');
            })
            ->with('post')
            ->latest()
            ->paginate(10, ['*'], 'commentsPage')
            ->withQueryString();

        return view('livewire.users.information.comments', ['comments' => $comments]);
    }
}
