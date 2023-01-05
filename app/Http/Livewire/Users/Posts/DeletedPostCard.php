<?php

namespace App\Http\Livewire\Users\Posts;

use App\Models\Post;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class DeletedPostCard extends Component
{
    use AuthorizesRequests;

    public Post $post;

    public function restore(int $id)
    {
        $post = Post::withTrashed()->find($id);

        $this->authorize('update', $post);

        $post->restore();

        return to_route('users.index', ['user' => auth()->id(), 'tab' => 'posts'])
            ->with('alert', ['status' => 'success', 'message' => '成功恢復文章！']);
    }

    public function render()
    {
        return view('livewire.users.posts.deleted-post-card');
    }
}
