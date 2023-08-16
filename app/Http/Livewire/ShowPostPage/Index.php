<?php

namespace App\Http\Livewire\ShowPostPage;

use App\Models\Post;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Index extends Component
{
    use AuthorizesRequests;

    public Post $post;

    public function mount(Post $post)
    {
        // private post, only the author can see
        if ($post->is_private) {
            $this->authorize('update', $post)->asNotFound();
        }

        $this->post = $post;
    }

    public function render()
    {
        // URL 修正，使用帶 slug 的網址
        if ($this->post->slug && $this->post->slug !== request()->slug) {
            redirect()->to($this->post->link_with_slug);
        }

        return view('livewire.show-post-page.index');
    }
}
