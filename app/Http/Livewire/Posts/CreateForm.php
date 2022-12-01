<?php

namespace App\Http\Livewire\Posts;

use App\Http\Traits\LivewirePostValidation;
use App\Models\Category;
use App\Models\Post;
use App\Services\FileService;
use App\Services\FormatTransferService;
use App\Services\PostService;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

/**
 * @property string $auto_save_key set by getAutoSaveKeyProperty()
 */
class CreateForm extends Component
{
    use LivewirePostValidation;
    use WithFileUploads;

    public $categories;

    public string $title = '';

    public int $categoryId = 1;

    public string $tags = '';

    public ?string $previewUrl = null;

    public $image;

    public string $body = '';

    protected $listeners = ['resetForm'];

    public function mount()
    {
        $this->categories = Category::all(['id', 'name']);

        if (Redis::exists($this->auto_save_key)) {
            $autoSavePostData = json_decode(Redis::get($this->auto_save_key), true);

            $this->title = $autoSavePostData['title'];
            $this->categoryId = (int) $autoSavePostData['category_id'];
            $this->tags = $autoSavePostData['tags'];
            $this->body = $autoSavePostData['body'];
        }
    }

    // computed property
    public function getAutoSaveKeyProperty(): string
    {
        return 'user_'.auth()->id().'_post_auto_save';
    }

    // when data update, auto save it to redis
    public function updated()
    {
        Redis::set($this->auto_save_key, json_encode(
            [
                'title' => $this->title,
                'category_id' => $this->categoryId,
                'tags' => $this->tags,
                'body' => $this->body,
            ], JSON_UNESCAPED_UNICODE)
        );

        // set ttl to 7 days
        Redis::expire($this->auto_save_key, 604_800);
    }

    public function updatedImage()
    {
        $this->validateImage();
    }

    public function store()
    {
        $this->validatePost();

        // xss filter
        $body = PostService::htmlPurifier($this->body);

        // upload image
        if ($this->image) {
            $imageName = FileService::generateImageFileName($this->image);
            $uploadFilePath = $this->image->storeAs('preview', $imageName, 's3');
            $this->previewUrl = Storage::disk('s3')->url($uploadFilePath);
        }

        $post = Post::query()->create([
            'user_id' => auth()->id(),
            'title' => $this->title,
            'category_id' => $this->categoryId,
            'body' => $body,
            'slug' => PostService::makeSlug($this->title),
            'preview_url' => $this->previewUrl,
            'excerpt' => PostService::makeExcerpt($body),
        ]);

        // convert tags json string to array
        $tagIdsArray = FormatTransferService::tagsJsonToTagIdsArray($this->tags);

        // create new tags relation with post in database
        $post->tags()->attach($tagIdsArray);

        Redis::del($this->auto_save_key);

        $this->dispatchBrowserEvent('leaveThePage', ['permit' => true]);

        return redirect()
            ->to($post->link_with_slug)
            ->with('alert', ['status' => 'success', 'message' => '成功新增文章！']);
    }

    public function resetForm()
    {
        $this->title = '';
        $this->categoryId = 1;
        $this->tags = '';
        $this->body = '';

        $this->dispatchBrowserEvent('removeAllTags');
        $this->dispatchBrowserEvent('resetCkeditorContent');
    }

    public function render()
    {
        return view('livewire.posts.create-form');
    }
}