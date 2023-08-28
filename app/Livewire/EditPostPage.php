<?php

namespace App\Livewire;

use App\Livewire\Traits\PostForm;
use App\Models\Category;
use App\Models\Post;
use App\Services\ContentService;
use App\Services\FileService;
use App\Services\FormatTransferService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditPostPage extends Component
{
    use PostForm;
    use AuthorizesRequests;
    use WithFileUploads;

    protected ContentService $contentService;

    protected FormatTransferService $formatTransferService;

    protected FileService $fileService;

    public Collection $categories;

    public Post $post;

    public function boot(
        ContentService $contentService,
        FormatTransferService $formatTransferService,
        FileService $fileService
    ): void {
        $this->contentService = $contentService;
        $this->formatTransferService = $formatTransferService;
        $this->fileService = $fileService;
    }

    public function mount(Post $post): void
    {
        $this->authorize('update', $post);

        $this->post = $post;
        $this->categories = Category::all(['id', 'name']);

        $this->user_id = $post->user_id;
        $this->category_id = $post->category_id;
        $this->is_private = $post->is_private;
        $this->preview_url = $post->preview_url;
        $this->title = $post->title;
        $this->body = $post->body;
        $this->tags = $post->tags_json;
    }

    public function updatedImage(): void
    {
        $this->validateImage();

        $this->resetValidation('image');
    }

    public function update(): void
    {
        $this->validatePost();

        $post = $this->updatePost($this->post);

        $this->dispatch('info-badge', status: 'success', message: '成功更新文章！');

        $this->redirect($post->link_with_slug, navigate: true);
    }

    #[Title('編輯文章')]
    public function render()
    {
        return view('livewire.edit-post-page');
    }
}
