<?php

namespace App\Http\Livewire\Posts;

use App\Models\Post;
use Livewire\Component;

class Show extends Component
{
    public Post $post;

    public function mount(Post $post)
    {
        $this->post = $post;
    }

    public function render()
    {
        // URL 修正，使用帶 slug 的網址
        if ($this->post->slug && $this->post->slug !== request()->slug) {
            redirect()->to($this->post->link_with_slug);
        }

        return view('livewire.posts.show');
    }
}
