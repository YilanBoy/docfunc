<?php

namespace App\Http\Livewire\Posts;

use App\Http\Traits\Livewire\PostForm;
use App\Models\Category;
use App\Models\Post;
use App\Services\ContentService;
use App\Services\FileService;
use App\Services\FormatTransferService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use PostForm;
    use AuthorizesRequests;
    use WithFileUploads;

    protected ContentService $contentService;

    protected FormatTransferService $formatTransferService;

    protected FileService $fileService;

    public Collection $categories;

    public string $autoSaveKey = '';

    public Post $default;

    public bool $isDataChanged = false;

    public function boot(
        ContentService $contentService,
        FormatTransferService $formatTransferService,
        FileService $fileService
    ): void {
        $this->contentService = $contentService;
        $this->formatTransferService = $formatTransferService;
        $this->fileService = $fileService;
    }

    public function mount(int $id): void
    {
        $this->autoSaveKey = 'auto_save_user_'.auth()->id().'_edit_post_'.$id;

        $this->default = Post::find($id);

        $this->authorize('update', $this->default);

        $this->categories = Category::all(['id', 'name']);

        if (! $this->getDataFromAutoSave($this->autoSaveKey)) {
            $this->post['category_id'] = $this->default->category_id;
            $this->post['is_private'] = $this->default->is_private;
            $this->post['preview_url'] = $this->default->preview_url;
            $this->post['title'] = $this->default->title;
            $this->post['body'] = $this->default->body;
            $this->post['tags'] = $this->default->tags_json;
        }
    }

    private function dataChangeCheck(): void
    {
        $originalPostPattern = $this->default->category_id;
        $originalPostPattern .= $this->default->is_private;
        $originalPostPattern .= $this->default->title;
        $originalPostPattern .= $this->default->tags_json;
        $originalPostPattern .= $this->default->body;

        $postPattern = $this->post['category_id'];
        $postPattern .= $this->post['is_private'];
        $postPattern .= $this->post['title'];
        $postPattern .= $this->post['tags'];
        $postPattern .= $this->contentService->htmlPurifier($this->post['body']);

        $originalPostHash = md5($originalPostPattern);
        $postHash = md5($postPattern);

        $this->isDataChanged = ($originalPostHash !== $postHash);
    }

    public function updated(): void
    {
        $this->autoSave($this->autoSaveKey);
        $this->dataChangeCheck();
    }

    public function resetForm(): void
    {
        $this->post['category_id'] = $this->default->category_id;
        $this->post['is_private'] = $this->default->is_private;
        $this->post['title'] = $this->default->title;
        $this->post['tags'] = $this->default->tags_json;
        // dispatch browser event to reset ckeditor content to default
        // when change the content, ckeditor change:data event will update the $this->post['body']
        $this->dispatchBrowserEvent('updateCkeditorContent', ['content' => $this->default->body]);

        // updated hook will only be triggered by wire:model
        // need to manually call updated()
        $this->updated();
    }

    public function update()
    {
        $this->validatePost();

        // upload image
        if ($this->post['image']) {
            $this->post['preview_url'] = $this->fileService->uploadImageToCloud($this->post['image']);
        }

        $this->post['body'] = $this->contentService->htmlPurifier($this->post['body']);

        $this->default->update([
            'title' => $this->post['title'],
            'slug' => $this->contentService->makeSlug($this->post['title']),
            'is_private' => $this->post['is_private'],
            'category_id' => $this->post['category_id'],
            'body' => $this->post['body'],
            'excerpt' => $this->contentService->makeExcerpt($this->post['body']),
            'preview_url' => $this->post['preview_url'],
        ]);

        $this->default->tags()->sync(
            $this->formatTransferService->tagsJsonToTagIdsArray($this->post['tags'])
        );

        $this->clearAutoSave($this->autoSaveKey);

        return redirect()
            ->to($this->default->link_with_slug)
            ->with('alert', ['status' => 'success', 'message' => '成功更新文章！']);
    }

    public function render()
    {
        return view('livewire.posts.edit');
    }
}
