<?php

namespace App\Http\Livewire\Posts;

use App\Http\Traits\LivewirePostValidation;
use App\Models\Category;
use App\Models\Post;
use App\Services\ContentService;
use App\Services\FileService;
use App\Services\FormatTransferService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditForm extends Component
{
    use LivewirePostValidation;
    use AuthorizesRequests;
    use WithFileUploads;

    protected ContentService $contentService;

    protected FormatTransferService $formatTransferService;

    protected FileService $fileService;

    public $categories;

    public int $postId;

    public Post $post;

    public string $title;

    public int $categoryId;

    public string $tags;

    public ?string $previewUrl = null;

    public $image = null;

    public string $body;

    public function boot(
        ContentService $contentService,
        FormatTransferService $formatTransferService,
        FileService $fileService
    ) {
        $this->contentService = $contentService;
        $this->formatTransferService = $formatTransferService;
        $this->fileService = $fileService;
    }

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

    public function updatedImage()
    {
        $this->validateImage();

        $this->resetValidation('image');
    }

    public function update()
    {
        $this->validatePost();

        $this->post->title = $this->title;
        $this->post->slug = $this->contentService->makeSlug($this->title);
        $this->post->category_id = $this->categoryId;

        $body = $this->contentService->htmlPurifier($this->body);
        $this->post->body = $body;
        $this->post->excerpt = $this->contentService->makeExcerpt($body);

        // upload image
        if ($this->image) {
            $imageName = $this->fileService->generateFileName($this->image->getClientOriginalExtension());
            $uploadFilePath = $this->image->storeAs('preview', $imageName, 's3');
            $this->previewUrl = Storage::disk('s3')->url($uploadFilePath);
        }

        $this->post->preview_url = $this->previewUrl;
        $this->post->save();

        $tagIdsArray = $this->formatTransferService->tagsJsonToTagIdsArray($this->tags);

        $this->post->tags()->sync($tagIdsArray);

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
