<?php

namespace App\Livewire\Posts;

use App\Http\Traits\Livewire\PostValidation;
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
    use PostValidation;
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

    public function mount(int $id): void
    {
        $this->autoSaveKey = 'auto_save_user_'.auth()->id().'_edit_post_'.$id;

        $this->post = Post::find($id);

        $this->authorize('update', $this->post);

        $this->categories = Category::all(['id', 'name']);

        if (! $this->getDataFromAutoSave($this->autoSaveKey)) {
            $this->category_id = $this->post->category_id;
            $this->is_private = $this->post->is_private;
            $this->preview_url = (string) $this->post->preview_url;
            $this->title = $this->post->title;
            $this->body = $this->post->body;
            $this->tags = $this->post->tags_json;
        }
    }

    public function update()
    {
        $this->validatePost();

        // upload image
        if ($this->image) {
            $this->preview_url = $this->fileService->uploadImageToCloud($this->image);
        }

        $this->body = $this->contentService->htmlPurifier($this->body);

        $this->post->update([
            'title' => $this->title,
            'slug' => $this->contentService->makeSlug($this->title),
            'is_private' => $this->is_private,
            'category_id' => $this->category_id,
            'body' => $this->body,
            'excerpt' => $this->contentService->makeExcerpt($this->body),
            'preview_url' => $this->preview_url,
        ]);

        $this->post->tags()->sync(
            $this->formatTransferService->tagsJsonToTagIdsArray($this->tags)
        );

        $this->clearAutoSave($this->autoSaveKey);

        $this->redirect($this->post->link_with_slug, navigate: true);

        $this->dispatch('info-badge', status: 'success', message: '成功更新文章！');
    }

    #[Title('編輯文章')]
    public function render()
    {
        return view('livewire.posts.edit-post-page');
    }
}
