<?php

namespace App\Livewire\Shared\Comments;

use App\Enums\CommentOrder;
use App\Models\Post;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

class Comments extends Component
{
    #[Locked]
    public int $postId;

    #[Locked]
    public int $postAuthorId;

    #[Locked]
    public int $maxLayer;

    public int $commentCounts;

    public CommentOrder $order = CommentOrder::POPULAR;

    // update comment count in post show page
    #[On('update-comment-counts')]
    public function updateCommentCounts(): void
    {
        $this->commentCounts = Post::findOrFail($this->postId, ['comment_counts'])->comment_counts;
    }

    public function changeOrder(CommentOrder $order): void
    {
        $this->order = $order;
    }

    public function render(): View
    {
        return view('livewire.shared.comments.comments');
    }
}
