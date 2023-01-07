<?php

namespace App\Http\Livewire\Users\Posts;

use App\Models\Post;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class DeletedPostCard extends Component
{
    use AuthorizesRequests;

    public $postId; //$post->id

    public $postTitle; // $post->title

    public $postCreatedAtDateString; // $post->created_at->toDateString()

    public $postCreatedAtDiffForHuman; // $post->created_at->diffForHumans()

    public $postWillDeletedAtDiffForHuman;

    public $postCommentCount; // $post->comment_count

    public $categoryName; // $post->category->name

    public $categoryIcon; // $post->category->icon

    public function restore(int $id)
    {
        $post = Post::withTrashed()->find($id);

        $this->authorize('update', $post);

        $post->restore();

        $this->emit('refreshUserPosts');

        $this->dispatchBrowserEvent('info-badge', ['status' => 'success', 'message' => '文章已恢復']);
    }

    public function render()
    {
        return view('livewire.users.posts.deleted-post-card');
    }
}
