<?php

namespace App\Livewire\Shared\Posts;

use App\Models\Post;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class ShowPostSidemenu extends Component
{
    use AuthorizesRequests;

    public $postId;

    public $postTitle;

    public $authorId;

    public function deletePost(Post $post)
    {
        $this->authorize('destroy', $post);

        $post->delete();

        return redirect()
            ->route('users.show', ['user' => auth()->id(), 'tab' => 'posts'])
            ->with('alert', ['status' => 'success', 'message' => '成功刪除文章！']);
    }

    public function render()
    {
        return view('livewire.shared.posts.show-post-sidemenu');
    }
}
