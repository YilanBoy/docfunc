<?php

namespace App\Http\Livewire\Posts\Partials;

use App\Models\Post;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class MobileShowMenu extends Component
{
    use AuthorizesRequests;

    public $postId; // $post->id

    public function deletePost(Post $post)
    {
        $this->authorize('destroy', $post);

        $post->delete();

        return to_route('users.index', ['user' => auth()->id(), 'tab' => 'posts'])
            ->with('alert', ['status' => 'success', 'message' => '成功刪除文章！']);
    }

    public function render()
    {
        return view('livewire.posts.partials.mobile-show-menu');
    }
}
