<?php

namespace App\Http\Livewire\User;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Comments extends Component
{
    use WithPagination;

    public int $userId;

    public function getQueryString()
    {
        return [];
    }

    public function render()
    {
        // 該會員的留言
        $comments = User::find($this->userId)->comments()
            ->whereHas('post', function ($query) {
                $query->whereNull('deleted_at');
            })
            ->with('post')
            ->latest()
            ->paginate(10);

        return view('livewire.user.comments', ['comments' => $comments]);
    }
}
