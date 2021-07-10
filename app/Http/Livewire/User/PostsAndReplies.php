<?php

namespace App\Http\Livewire\User;

use Livewire\Component;
use Livewire\WithPagination;

class PostsAndReplies extends Component
{
    use WithPagination;

    public $currentUrl;
    public $user;
    public $tab;

    protected $queryString = [
        'tab',
    ];

    public function mount()
    {
        $this->tab = request()->tab ?? 'posts';
    }

    public function setTab($newTab)
    {
        $this->resetPage();
        $this->tab = $newTab;
    }

    public function render()
    {
        // 該會員的文章
        $posts = $this->user->posts()->withTrashed()->with('category')->latest()->paginate(5);
        // 該會員的留言
        $replies = $this->user->replies()->whereHas('post', function ($query) {
            return $query->whereNull('deleted_at');
        })->with('post')->latest()->paginate(5);

        return view('livewire.user.posts-and-replies', [
            'posts' => $posts,
            'replies' => $replies,
        ]);
    }
}
