<?php

namespace App\Livewire;

use App\Http\Traits\Livewire\PostForm;
use App\Models\Category;
use App\Models\Post;
use App\Services\ContentService;
use App\Services\FileService;
use App\Services\FormatTransferService;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreatePostPage extends Component
{
    use PostForm;
    use WithFileUploads;

    protected ContentService $contentService;

    protected FormatTransferService $formatTransferService;

    protected FileService $fileService;

    public Collection $categories;

    public function boot(
        ContentService $contentService,
        FormatTransferService $formatTransferService,
        FileService $fileService
    ): void {
        $this->contentService = $contentService;
        $this->formatTransferService = $formatTransferService;
        $this->fileService = $fileService;
    }

    public function mount(): void
    {
        $this->autoSaveKey = 'auto_save_user_'.auth()->id().'_create_post';

        $this->categories = Category::all(['id', 'name']);

        $this->setDataFromAutoSave($this->autoSaveKey);
    }

    public function store()
    {
        $this->validatePost();

        // upload image
        if ($this->image) {
            $this->preview_url = $this->fileService->uploadImageToCloud($this->image);
        }

        // xss filter
        $this->body = $this->contentService->htmlPurifier($this->body);

        $post = Post::query()->create([
            'user_id' => auth()->id(),
            'title' => $this->title,
            'category_id' => $this->category_id,
            'body' => $this->body,
            'is_private' => $this->is_private,
            'slug' => $this->contentService->makeSlug($this->title),
            'preview_url' => $this->preview_url,
            'excerpt' => $this->contentService->makeExcerpt($this->body),
        ]);

        // create new tags relation with post in database
        $post->tags()->attach(
            $this->formatTransferService->tagsJsonToTagIdsArray($this->tags)
        );

        // delete auto save data
        $this->clearAutoSave($this->autoSaveKey);

        $this->dispatch('info-badge', status: 'success', message: '成功新增文章！');

        $this->redirect($post->link_with_slug, navigate: true);
    }

    #[Title('新增文章')]
    public function render()
    {
        return view('livewire.create-post-page');
    }
}
