<?php

namespace App\Http\Livewire;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;

class Posts extends Component
{
    use WithPagination;

    public $category;
    public $tag;
    public $order;

    protected $queryString = [
        'order',
    ];

    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->order = request()->order ?? 'latest';
    }

    public function setOrder($newOrder)
    {
        // 回到第一頁
        $this->resetPage();
        $this->order = $newOrder;
    }

    public function render()
    {
        if ($this->category) {
            $postQuery = $this->category->posts();
        } elseif ($this->tag) {
            $postQuery = $this->tag->posts();
        } else {
            $postQuery = Post::query();
        }

        $posts = $postQuery->withOrder($this->order)
            ->with('user', 'category') // 預加載防止 N+1 問題
            ->paginate(10);

        return view('livewire.posts', [
            'posts' => $posts,
        ]);
    }
}
