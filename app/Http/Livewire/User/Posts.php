<?php

namespace App\Http\Livewire\User;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Posts extends Component
{
    use WithPagination;

    public int $userId;

    public function getQueryString()
    {
        // 避免 paginate 在 url 中加入 query string
        return [];
    }

    public function render()
    {
        // 該會員的文章
        $posts = User::find($this->userId)->posts()
            ->withTrashed()
            ->with('category')
            ->orderBy('deleted_at', 'desc')
            ->latest()
            ->paginate(10);

        return view('livewire.user.posts', ['posts' => $posts]);
    }
}
