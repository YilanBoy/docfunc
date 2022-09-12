<?php

namespace App\Http\Livewire\Posts;

use App\Models\Category;
use App\Models\Post;
use App\Services\FileService;
use App\Services\FormatTransferService;
use App\Services\PostService;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateForm extends Component
{
    use WithFileUploads;

    public string $autoSaveKey;

    public $categories;

    public string $title = '';

    public ?int $category_id = null;

    public string $tags = '';

    public $photo;

    public string $body = '';

    public function mount()
    {
        $this->autoSaveKey = 'user_'.auth()->id().'_post_auto_save';
        $this->categories = Category::all(['id', 'name']);

        if (Redis::exists($this->autoSaveKey)) {
            $autoSavePostData = json_decode(Redis::get($this->autoSaveKey), true);

            $this->title = $autoSavePostData['title'];
            $this->category_id = (int) $autoSavePostData['category_id'];
            $this->tags = $autoSavePostData['tags'];
            $this->body = $autoSavePostData['body'];
        }
    }

    // when data update, auto save it to redis
    public function updated()
    {
        Redis::set($this->autoSaveKey, json_encode(
            [
                'title' => $this->title,
                'category_id' => $this->category_id,
                'tags' => $this->tags,
                'body' => $this->body,
            ], JSON_UNESCAPED_UNICODE)
        );

        // set ttl to 7 days
        Redis::expire($this->autoSaveKey, 604_800);
    }

    public function updatedPhoto()
    {
        $validator = Validator::make(
            ['photo' => $this->photo],
            [
                'photo' => ['image', 'max:1024'], // 1MB Max
            ],
            [
                'photo.image' => '圖片格式有誤',
                'photo.max' => '圖片大小不能超過 1024 KB',
            ]
        );

        if ($validator->fails()) {
            $this->dispatchBrowserEvent('scroll-to-top');
        }

        $validator->validate();
    }

    public function store()
    {
        $validator = Validator::make(
            [
                'title' => $this->title,
                'category_id' => $this->category_id,
                'photo' => $this->photo,
                // validate body text character count
                'body' => preg_replace('/[\r\n]/u', '', strip_tags($this->body)),
            ],
            [
                'title' => ['required', 'min:4', 'max:50'],
                'category_id' => ['required', 'numeric', 'exists:categories,id'],
                'photo' => ['nullable', 'image', 'max:1024'],
                'body' => ['required', 'min:500', 'max:20000'],
            ],
            [
                'title.required' => '請填寫標題',
                'title.min' => '標題至少 4 個字元',
                'title.max' => '標題至多 50 個字元',
                'category_id.required' => '請選擇文章分類',
                'category_id.numeric' => '分類資料錯誤',
                'category_id.exists' => '分類不存在',
                'photo.image' => '圖片格式有誤',
                'photo.max' => '圖片大小不能超過 1024 KB',
                'body.required' => '請填寫文章內容',
                'body.min' => '文章內容至少 500 個字元',
                'body.max' => '文章內容字數已超過 20,000 個字元',
            ]
        );

        if ($validator->fails()) {
            $this->dispatchBrowserEvent('scroll-to-top');
        }

        $validator->validate();

        $postService = new PostService();
        $formatTransferService = new FormatTransferService();

        // xss filter
        $body = $postService->htmlPurifier($this->body);

        $previewUrl = null;

        // upload photo
        if ($this->photo) {
            $imageName = FileService::generateImageFileName($this->photo);
            $uploadFilePath = $this->photo->storeAs('preview', $imageName, 's3');
            $previewUrl = Storage::disk('s3')->url($uploadFilePath);
        }

        $post = Post::create([
            'user_id' => auth()->id(),
            'title' => $this->title,
            'category_id' => $this->category_id,
            'body' => $body,
            'slug' => $postService->makeSlug($this->title),
            'preview_url' => $previewUrl,
            'excerpt' => $postService->makeExcerpt($body),
        ]);

        // convert tags json string to array
        $tagIdsArray = $formatTransferService->tagsJsonToTagIdsArray($this->tags);

        // create new tags relation with post in database
        $post->tags()->attach($tagIdsArray);

        Redis::del($this->autoSaveKey);

        $this->dispatchBrowserEvent('leaveThePage', ['permit' => true]);

        return redirect()
            ->to($post->link_with_slug)
            ->with('alert', ['status' => 'success', 'message' => '成功新增文章！']);
    }

    public function render()
    {
        return view('livewire.posts.create-form');
    }
}
