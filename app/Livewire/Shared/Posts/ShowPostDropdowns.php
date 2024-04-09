<?php

namespace App\Livewire\Shared\Posts;

use App\Models\Post;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class ShowPostDropdowns extends Component
{
    use AuthorizesRequests;

    #[Locked]
    public int $postId;

    public function deletePost(Post $post): void
    {
        $this->authorize('destroy', $post);

        $post->delete();

        $this->dispatch('info-badge', status: 'success', message: '成功刪除文章！');

        $this->redirect(route('users.show', ['user' => auth()->id(), 'tab' => 'posts']));
    }

    public function render(): View
    {
        return view('livewire.shared.posts.show-post-dropdowns');
    }
}
