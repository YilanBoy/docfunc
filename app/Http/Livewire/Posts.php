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
            $post = $this->category->posts();
        } elseif ($this->tag) {
            $post = $this->tag->posts();
        } else {
            $post = Post::query();
        }

        $posts = $post->withOrder($this->order)
            ->with('user', 'category') // 預加載防止 N+1 問題
            ->paginate(10);

        return view('livewire.posts', [
            'posts' => $posts,
        ]);
    }
}
