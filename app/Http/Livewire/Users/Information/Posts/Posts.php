<?php

namespace App\Http\Livewire\Users\Information\Posts;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;

class Posts extends Component
{
    use WithPagination;

    public int $userId;

    protected $listeners = ['refreshUserPosts' => '$refresh'];

    public function hydrate()
    {
        $this->dispatchBrowserEvent('scroll-to-top');
    }

    public function render()
    {
        $posts = Post::whereUserId($this->userId)
            ->withTrashed()
            ->with('category')
            ->orderBy('deleted_at', 'desc')
            ->latest()
            ->paginate(10, ['*'], 'postsPage')
            ->withQueryString();

        // 該會員的文章
        return view('livewire.users.information.posts.posts', ['posts' => $posts]);
    }
}
