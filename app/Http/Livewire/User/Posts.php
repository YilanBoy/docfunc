<?php

namespace App\Http\Livewire\User;

use Livewire\Component;
use Livewire\WithPagination;

class Posts extends Component
{
    use WithPagination;

    public $user;

    public function getQueryString()
    {
        // 避免 paginate 在 url 中加入 query string
        return [];
    }

    public function render()
    {
        // 該會員的文章
        $posts = $this->user->posts()
            ->withTrashed()
            ->with('category')
            ->orderBy('deleted_at', 'desc')
            ->latest()
            ->paginate(5);

        return view('livewire.user.posts', [
            'posts' => $posts,
        ]);
    }
}
