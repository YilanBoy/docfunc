<?php

namespace App\Livewire\Components\Comments;

use App\Models\Post;
use Livewire\Attributes\On;
use Livewire\Component;

class Reply extends Component
{
    public int $postId;

    public int $commentCounts;

    // update comment count in post show page
    #[On('update-comment-counts')]
    public function updateCommentCounts(): void
    {
        $this->commentCounts = Post::findOrFail($this->postId, ['comment_counts'])->comment_counts;
    }

    public function render()
    {
        return view('livewire.components.comments.reply');
    }
}
