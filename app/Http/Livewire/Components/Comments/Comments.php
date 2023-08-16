<?php

namespace App\Http\Livewire\Components\Comments;

use Livewire\Component;

class Comments extends Component
{
    public int $postId;

    public int $commentCounts;

    public int $count = 10;

    public int $perPage = 10;

    public bool $showMoreButtonIsActive = false;

    public function mount()
    {
        // 當留言總數大於每頁數目，需要顯示「顯示更多留言」的按鈕
        if ($this->commentCounts > $this->perPage) {
            $this->showMoreButtonIsActive = true;
        }
    }

    // 顯示更多留言
    public function showMore()
    {
        $this->count += $this->perPage;

        // 當父留言顯示數目已經超過文章的父留言總數，不顯示「顯示更多留言」的按鈕
        if ($this->count >= $this->commentCounts) {
            $this->showMoreButtonIsActive = false;
        }
    }

    public function render()
    {
        return view('livewire.components.comments.comments');
    }
}
