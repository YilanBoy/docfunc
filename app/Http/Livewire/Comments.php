<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Comments extends Component
{
    public $post;
    public $count = 10;
    public $perPage = 10;
    public $showMoreButtonIsActive = false;
    public $parentCommentsCount;

    public function mount()
    {
        // 父留言的數目
        $this->parentCommentsCount = $this->post->comments()->whereNull('parent_id')->count();

        // 當父留言總數大於每頁數目，需要顯示「顯示更多留言」的按鈕
        if ($this->parentCommentsCount > $this->perPage) {
            $this->showMoreButtonIsActive = true;
        }
    }

    // 顯示更多留言
    public function showMore()
    {
        $this->count += $this->perPage;

        // 當父留言顯示數目已經超過文章的父留言總數，不顯示「顯示更多留言」的按鈕
        if ($this->count >= $this->parentCommentsCount) {
            $this->showMoreButtonIsActive = false;
        }
    }

    public function render()
    {
        return view('livewire.comments');
    }
}
