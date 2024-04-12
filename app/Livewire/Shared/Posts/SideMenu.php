<?php

namespace App\Livewire\Shared\Posts;

use App\Models\Post;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Component;

class SideMenu extends Component
{
    use AuthorizesRequests;

    public $postId;

    public $postTitle;

    public $authorId;

    public function deletePost(Post $post): void
    {
        $this->authorize('destroy', $post);

        $post->delete();

        $this->dispatch('info-badge', status: 'success', message: '成功刪除文章！');

        $this->redirectRoute('users.show', ['user' => auth()->id(), 'tab' => 'posts'], navigate: true);
    }

    public function render(): View
    {
        return view('livewire.shared.posts.side-menu');
    }
}
