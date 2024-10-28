<?php

namespace App\Livewire\Pages\Posts;

use App\Models\Post;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class ShowPostPage extends Component
{
    use AuthorizesRequests;

    /**
     * @var int get the post id from url path
     *
     * why not using implicit model binding?
     *
     * user can delete post in side menu, but there is a problem if you use implicit binding.
     * although the page will redirect after deleting the post,
     * but livewire will still try to hydrate the component before jumping.
     * at this time, post model 404 not found errors will occasionally occur.
     */
    public int $postId;

    #[Locked]
    public int $maxCommentLayer = 2;

    public function render(): View
    {
        $post = Post::findOrFail($this->postId);

        // private post, only the author can see
        if ($post->is_private) {
            $this->authorize('update', $post)->asNotFound();
        }

        // URL 修正，使用帶 slug 的網址
        if ($post->slug && $post->slug !== request()->slug) {
            redirect()->to($post->link_with_slug);
        }

        return view('livewire.pages.posts.show-post-page', compact('post'))
            ->title($post->title);
    }
}
