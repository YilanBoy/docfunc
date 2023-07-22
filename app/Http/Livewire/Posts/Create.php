<?php

namespace App\Http\Livewire\Posts;

use App\Http\Traits\Livewire\PostValidation;
use App\Models\Category;
use App\Models\Post;
use App\Services\ContentService;
use App\Services\FileService;
use App\Services\FormatTransferService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

/**
 * @property mixed $auto_save_key computed property, create by getAutoSaveKeyProperty()
 */
class Create extends Component
{
    use PostValidation;
    use WithFileUploads;

    protected ContentService $contentService;

    protected FormatTransferService $formatTransferService;

    protected FileService $fileService;

    public Collection $categories;

    public string $title = '';

    public int $categoryId = 1;

    public string $tags = '';

    public ?string $previewUrl = null;

    public $image = null;

    public string $body = '';

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

    // computed property
    public function getAutoSaveKeyProperty(): string
    {
        return 'auto_save_user_'.auth()->id().'_create_post';
    }

    public function mount(): void
    {
        $this->categories = Category::all(['id', 'name']);

        if (Cache::has($this->auto_save_key)) {
            $autoSavePostData = json_decode(Cache::get($this->auto_save_key), true);

            $this->title = $autoSavePostData['title'];
            $this->categoryId = (int) $autoSavePostData['category_id'];
            $this->tags = $autoSavePostData['tags'];
            $this->body = $autoSavePostData['body'];
        }
    }

    // when image property update, trigger validation
    public function updatedImage(): void
    {
        $this->validateImage();

        // when the image pass the validation, clear the error message
        $this->resetValidation('image');
    }

    // when data update, auto save it to redis
    public function updated(): void
    {
        Cache::put(
            $this->auto_save_key,
            json_encode([
                'title' => $this->title,
                'category_id' => $this->categoryId,
                'tags' => $this->tags,
                'body' => $this->body,
            ], JSON_UNESCAPED_UNICODE),
            now()->addDays(7)
        );
    }

    public function clearForm(): void
    {
        $this->title = '';
        $this->categoryId = 1;
        $this->tags = '';
        $this->body = '';

        $this->dispatchBrowserEvent('removeAllTags');
        $this->dispatchBrowserEvent('clearCkeditorContent');
    }

    public function store()
    {
        $this->validatePost();

        // xss filter
        $body = $this->contentService->htmlPurifier($this->body);

        // upload image
        if ($this->image) {
            $imageName = $this->fileService->generateFileName($this->image->getClientOriginalExtension());
            $uploadFilePath = $this->image->storeAs('preview', $imageName, 's3');
            $this->previewUrl = Storage::disk('s3')->url($uploadFilePath);
        }

        $post = Post::query()->create([
            'user_id' => auth()->id(),
            'title' => $this->title,
            'category_id' => $this->categoryId,
            'body' => $body,
            'slug' => $this->contentService->makeSlug($this->title),
            'preview_url' => $this->previewUrl,
            'excerpt' => $this->contentService->makeExcerpt($body),
        ]);

        // convert tags json string to array
        $tagIdsArray = $this->formatTransferService->tagsJsonToTagIdsArray($this->tags);

        // create new tags relation with post in database
        $post->tags()->attach($tagIdsArray);

        // delete auto save data
        Cache::pull($this->auto_save_key);

        $this->dispatchBrowserEvent('leavePage', ['leavePagePermission' => true]);

        return redirect()
            ->to($post->link_with_slug)
            ->with('alert', ['status' => 'success', 'message' => '成功新增文章！']);
    }

    public function render()
    {
        return view('livewire.posts.create');
    }
}
