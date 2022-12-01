<?php

namespace App\Http\Livewire\Users\Posts;

use App\Models\Post;
use Livewire\Component;

class DeletedPostCard extends Component
{
    public Post $post;

    public function render()
    {
        return view('livewire.users.posts.deleted-post-card');
    }
}
