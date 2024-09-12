<?php

namespace App\Livewire\Pages\Posts;

use App\Models\Post;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Component;

class ShowPostPage extends Component
{
    use AuthorizesRequests;

    public Post $post;

    public function mount(Post $post): void
    {
        // private post, only the author can see
        if ($post->is_private) {
            $this->authorize('update', $post)->asNotFound();
        }

        $this->post = $post;
    }

    public function render(): View
    {
        // URL 修正，使用帶 slug 的網址
        if ($this->post->slug && $this->post->slug !== request()->slug) {
            redirect()->to($this->post->link_with_slug);
        }

        return view('livewire.pages.posts.show-post-page')->title($this->post->title);
    }
}
