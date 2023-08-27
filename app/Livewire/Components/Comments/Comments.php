<?php

namespace App\Livewire\Components\Comments;

use App\Models\Comment;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Comments extends Component
{
    #[Locked]
    public int $postId;

    #[Locked]
    public int $postAuthorId;

    public int $perPage = 10;

    public array $groupIds = [];

    public int $bookmark = 0;

    public bool $showMoreButtonIsActive = true;

    public function mount(): void
    {
        $commentIds = Comment::query()
            ->where('post_id', $this->postId)
            ->orderBy('id', 'desc')
            ->limit($this->perPage + 1)
            ->pluck('id');

        if ($commentIds->count() > 0) {
            $this->groupIds[$commentIds->first()] = array_slice($commentIds->all(), 0, $this->perPage);
            $this->bookmark = $commentIds->last();
        }

        if ($commentIds->count() <= $this->perPage) {
            $this->showMoreButtonIsActive = false;
        }
    }

    // 顯示更多留言
    public function showMore(): void
    {
        $commentIds = Comment::query()
            ->where('post_id', $this->postId)
            ->where('id', '<=', $this->bookmark)
            ->orderBy('id', 'desc')
            ->limit($this->perPage + 1)
            ->pluck('id');

        if ($commentIds->count() > 0) {
            $this->groupIds[$commentIds->first()] = array_slice($commentIds->all(), 0, $this->perPage);
            $this->bookmark = $commentIds->last();
        }

        if ($commentIds->count() <= $this->perPage) {
            $this->showMoreButtonIsActive = false;
        }
    }

    public function render()
    {
        return view('livewire.components.comments.comments');
    }
}
