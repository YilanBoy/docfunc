<?php

namespace App\Livewire\Shared\Posts;

use App\Models\Post;
use Livewire\Attributes\Url;
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

    #[Url]
    public string $order = 'latest';

    // add a scroll to top browser event when the paginators are updated
    public function updatedPaginators(): void
    {
        $javaScript = <<<'JS'
            window.scrollTo({ top: 0 });
        JS;

        $this->js($javaScript);
    }

    public function orderChange($newOrder): void
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
            ->where('is_private', false)
            ->withOrder($this->order)
            ->with('user', 'category', 'tags') // 預加載防止 N+1 問題
            ->withCount('tags') // 計算標籤數目
            ->paginate(10)
            ->withQueryString();

        return view('livewire.shared.posts.posts', ['posts' => $posts]);
    }
}
