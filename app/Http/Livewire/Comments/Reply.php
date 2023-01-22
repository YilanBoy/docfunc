<?php

namespace App\Http\Livewire\Comments;

use App\Models\Post;
use Livewire\Component;

class Reply extends Component
{
    public int $postId;

    public int $commentCount;

    protected $listeners = ['updateCommentCount'];

    // update comment count in post show page
    public function updateCommentCount()
    {
        $this->commentCount = Post::findOrFail($this->postId, ['comment_count'])->comment_count;
    }

    public function render()
    {
        return view('livewire.comments.reply');
    }
}
