<?php

namespace App\Http\Livewire\Users\Posts;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;

class Posts extends Component
{
    use WithPagination;

    public int $userId;

    public function hydrate()
    {
        $this->dispatchBrowserEvent('scroll-to-top');
    }

    public function render()
    {
        // 該會員的文章
        $posts = Post::whereUserId($this->userId)
            ->withTrashed()
            ->with('category')
            ->orderBy('deleted_at', 'desc')
            ->latest()
            ->paginate(10, ['*'], 'postsPage')
            ->withQueryString();

        return view('livewire.users.posts.posts', ['posts' => $posts]);
    }
}
