<?php

namespace App\Http\Livewire\Users;

use App\Models\User;
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
        $comments = User::findOrFail($this->userId)
            ->comments()
            ->whereHas('post', function ($query) {
                $query->whereNull('deleted_at');
            })
            ->with('post')
            ->latest()
            ->paginate(10, ['*'], 'commentsPage')
            ->withQueryString();

        return view('livewire.users.comments', ['comments' => $comments]);
    }
}
