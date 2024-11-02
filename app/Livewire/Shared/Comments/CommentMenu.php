<?php

namespace App\Livewire\Shared\Comments;

use App\Models\Post;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

class CommentMenu extends Component
{
    #[Locked]
    public int $postId;

    public int $commentCounts;

    // update comment count in post show page
    #[On('update-comment-counts')]
    public function updateCommentCounts(): void
    {
        $this->commentCounts = Post::findOrFail($this->postId, ['comment_counts'])->comment_counts;
    }

    public function render(): View
    {
        return view('livewire.shared.comments.comment-menu');
    }
}
