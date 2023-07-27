<?php

namespace App\Http\Livewire\Posts;

use App\Http\Traits\Livewire\PostForm;
use App\Models\Category;
use App\Models\Post;
use App\Services\ContentService;
use App\Services\FileService;
use App\Services\FormatTransferService;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use PostForm;
    use WithFileUploads;

    protected ContentService $contentService;

    protected FormatTransferService $formatTransferService;

    public string $autoSaveKey = '';

    protected FileService $fileService;

    public Collection $categories;

    protected $listeners = ['clearForm'];

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

        $this->getDataFromAutoSave($this->autoSaveKey);
    }

    // when data update, auto save it to redis
    public function updated(): void
    {
        $this->autoSave($this->autoSaveKey);
    }

    public function clearForm(): void
    {
        $this->post['category_id'] = 1;
        $this->post['is_private'] = false;
        $this->post['title'] = '';

        // dispatch browser event to update the body and tags in front-end
        // when update these value, listener will be triggered and update the livewire property
        $this->dispatchBrowserEvent('update-ckeditor-content', ['content' => '']);
        $this->dispatchBrowserEvent('update-tags', ['tags' => json_encode([])]);
    }

    public function store()
    {
        $this->validatePost();

        // upload image
        if ($this->post['image']) {
            $this->post['preview_url'] = $this->fileService->uploadImageToCloud($this->post['image']);
        }

        // xss filter
        $this->post['body'] = $this->contentService->htmlPurifier($this->post['body']);

        $post = Post::query()->create([
            'user_id' => auth()->id(),
            'title' => $this->post['title'],
            'category_id' => $this->post['category_id'],
            'body' => $this->post['body'],
            'is_private' => $this->post['is_private'],
            'slug' => $this->contentService->makeSlug($this->post['title']),
            'preview_url' => $this->post['preview_url'],
            'excerpt' => $this->contentService->makeExcerpt($this->post['body']),
        ]);

        // create new tags relation with post in database
        $post->tags()->attach(
            $this->formatTransferService->tagsJsonToTagIdsArray($this->post['tags'])
        );

        // delete auto save data
        $this->clearAutoSave($this->autoSaveKey);

        return redirect()
            ->to($post->link_with_slug)
            ->with('alert', ['status' => 'success', 'message' => '成功新增文章！']);
    }

    public function render()
    {
        return view('livewire.posts.create');
    }
}
