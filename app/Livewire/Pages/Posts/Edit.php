<?php

namespace App\Livewire\Pages\Posts;

use App\Livewire\Forms\PostForm;
use App\Models\Category;
use App\Models\Post;
use App\Services\FileService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use AuthorizesRequests;
    use WithFileUploads;

    public PostForm $form;

    public Collection $categories;

    public Post $post;

    public function mount(Post $post): void
    {
        $this->authorize('update', $post);

        $this->post = $post;
        $this->categories = Category::all(['id', 'name']);

        $this->form->user_id = $post->user_id;
        $this->form->category_id = $post->category_id;
        $this->form->is_private = $post->is_private;
        $this->form->preview_url = $post->preview_url;
        $this->form->title = $post->title;
        $this->form->body = $post->body;
        $this->form->tags = $post->tags_json;
    }

    public function update(): void
    {
        $this->form->validatePost();

        // upload image
        if ($this->form->image) {
            $this->form->preview_url = app(FileService::class)
                ->uploadImageToCloud($this->form->image);
        }

        $post = $this->form->updatePost($this->post);

        $this->dispatch('info-badge', status: 'success', message: '成功更新文章！');

        $this->redirect($post->link_with_slug, navigate: true);
    }

    #[Title('編輯文章')]
    public function render()
    {
        return view('livewire.pages.posts.edit');
    }
}
