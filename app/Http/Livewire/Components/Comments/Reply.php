<?php

namespace App\Http\Livewire\Components\Comments;

use App\Models\Post;
use Livewire\Component;

class Reply extends Component
{
    public int $postId;

    public int $commentCounts;

    protected $listeners = ['updateCommentCounts'];

    // update comment count in post show page
    public function updateCommentCounts()
    {
        $this->commentCounts = Post::findOrFail($this->postId, ['comment_counts'])->comment_counts;
    }

    public function render()
    {
        return view('livewire.components.comments.reply');
    }
}
