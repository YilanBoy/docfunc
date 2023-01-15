<?php

namespace App\Http\Livewire\Users\Information\Posts;

use App\Models\Post;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class PostCard extends Component
{
    use AuthorizesRequests;

    public Post $post;

    public $postId; //$post->id

    public $postTitle; // $post->title

    public $postLink; // $post->link_with_slug

    public $postAuthorId; // $post->user_id

    public $postCreatedAtDateString; // $post->created_at->toDateString()

    public $postCreatedAtDiffForHuman; // $post->created_at->diffForHumans()

    public $postCommentCount; // $post->comment_count

    public $categoryLink; // $post->category->link_with_name

    public $categoryName; // $post->category->name

    public $categoryIcon; // $post->category->icon

    public function deletePost(Post $post)
    {
        $this->authorize('destroy', $post);

        $post->delete();

        $this->emit('refreshUserPosts');

        $this->dispatchBrowserEvent('info-badge', ['status' => 'success', 'message' => '成功刪除文章！']);
    }

    public function render()
    {
        return view('livewire.users.information.posts.post-card');
    }
}
