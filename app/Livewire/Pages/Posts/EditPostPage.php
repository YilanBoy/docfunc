<?php

namespace App\Livewire\Pages\Posts;

use App\Livewire\Forms\PostForm;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditPostPage extends Component
{
    use AuthorizesRequests;
    use WithFileUploads;

    #[Locked]
    public int $postId;

    public PostForm $form;

    public Collection $categories;

    public function mount(): void
    {
        $post = Post::findOrFail($this->postId);

        $this->authorize('update', $post);

        $this->categories = Category::all(['id', 'name']);

        $this->form->setPost($post);
    }

    public function update(Post $post): void
    {
        $this->form->validatePost();

        $this->form->uploadPreviewImage();

        $post = $this->form->updatePost($post);

        $this->dispatch('info-badge', status: 'success', message: '成功更新文章！');

        $this->redirect($post->link_with_slug, navigate: true);
    }

    #[Title('編輯文章')]
    public function render(): View
    {
        return view('livewire.pages.posts.edit-post-page');
    }
}
