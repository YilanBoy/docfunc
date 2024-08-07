<?php

namespace App\Livewire\Shared\Posts;

use App\Models\Post;
use Illuminate\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Posts extends Component
{
    use WithPagination;

    public int $categoryId = 0;

    public string $categoryName = '';

    public string $categoryDescription = '';

    public int $tagId = 0;

    public string $tagName = '';

    #[Url]
    public string $order = 'latest';

    public function changeOrder(string $newOrder): void
    {
        // 回到第一頁
        $this->resetPage();
        $this->order = $newOrder;
    }

    public function render(): View
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
            ->where('is_private', false)
            ->withOrder($this->order)
            ->with('user', 'category', 'tags') // 預加載防止 N+1 問題
            ->withCount('tags') // 計算標籤數目
            ->paginate(10)
            ->withQueryString();

        return view('livewire.shared.posts.posts', compact('posts'));
    }
}
