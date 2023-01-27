<?php

namespace App\Http\Livewire\Posts;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;

class Posts extends Component
{
    use WithPagination;

    public string $currentUrl;

    public int $categoryId = 0;

    public string $categoryName = '';

    public string $categoryDescription = '';

    public int $tagId = 0;

    public string $tagName = '';

    public string $order = '';

    protected $queryString = [
        'order',
    ];

    public function mount()
    {
        $this->order = request()->order ?? 'latest';
    }

    public function dehydrate()
    {
        // when change page, scroll to top
        $this->dispatchBrowserEvent('scroll-to-top');
    }

    public function orderChange($newOrder)
    {
        // 回到第一頁
        $this->resetPage();
        $this->order = $newOrder;
    }

    public function render()
    {
        $posts = Post::query()
            ->when($this->categoryId, function ($query) {
                return $query->where('category_id', $this->categoryId);
            })
            ->when($this->tagId, function ($query) {
                return $query->whereHas('tags', function ($query) {
                    $query->where('tag_id', $this->tagId);
                });
            })
            ->withOrder($this->order)
            ->with('user', 'category', 'tags') // 預加載防止 N+1 問題
            ->withCount('tags') // 計算標籤數目
            ->paginate(10)
            ->withQueryString();

        return view('livewire.posts.posts', ['posts' => $posts]);
    }
}
