<?php

namespace App\Http\Livewire\User;

use Livewire\Component;
use Livewire\WithPagination;

class Replies extends Component
{
    use WithPagination;

    public $user;

    public function getQueryString()
    {
        return [];
    }

    public function render()
    {
        // 該會員的留言
        $replies = $this->user->replies()
            ->whereHas('post', function ($query) {
                $query->whereNull('deleted_at');
            })
            ->with('post')
            ->latest()
            ->paginate(10);

        return view('livewire.user.replies', ['replies' => $replies]);
    }
}
