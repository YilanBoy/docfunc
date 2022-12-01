<?php

namespace App\Http\Livewire\Users\Posts;

use App\Models\Post;
use Livewire\Component;

class PostCard extends Component
{
    public Post $post;

    public function render()
    {
        return view('livewire.users.posts.post-card');
    }
}
