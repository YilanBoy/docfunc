<?php

namespace App\Http\Livewire\Posts;

use App\Http\Traits\LivewirePostForm;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditForm extends Component
{
    use LivewirePostForm;
    use AuthorizesRequests;
    use WithFileUploads;

    public $categories;

    public int $postId;

    public Post $post;

    public string $title;

    public int $categoryId;

    public string $tags;

    public ?string $previewUrl = null;

    public $photo;

    public string $body;

    public function mount()
    {
        $this->post = Post::find($this->postId);

        $this->authorize('update', $this->post);

        $this->categories = Category::all(['id', 'name']);

        $this->title = $this->post->title;
        $this->categoryId = $this->post->category_id;
        $this->tags = $this->post->tags_json;
        $this->previewUrl = $this->post->preview_url;
        $this->body = $this->post->body;
    }

    public function updatedPhoto()
    {
        $this->validatePhoto();
    }

    public function update()
    {
        $this->validatePost();

        $this->updatePost();

        $this->dispatchBrowserEvent('leaveThePage', ['permit' => true]);

        return redirect()
            ->to($this->post->link_with_slug)
            ->with('alert', ['status' => 'success', 'message' => '成功更新文章！']);
    }

    public function render()
    {
        return view('livewire.posts.edit-form');
    }
}
