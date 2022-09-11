<?php

namespace App\Http\Livewire\Posts;

use App\Models\Category;
use App\Models\Post;
use App\Services\FileService;
use App\Services\FormatTransferService;
use App\Services\PostService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditForm extends Component
{
    use AuthorizesRequests;
    use WithFileUploads;

    public $categories;

    public int $postId;

    public Post $post;

    public string $title;

    public int $category_id;

    public string $tags;

    public ?string $previewUrl = null;

    public $photo;

    public string $body;

    public function mount()
    {
        $this->post = Post::find($this->postId);

        $this->authorize('update', $this->post);

        $this->categories = Category::all(['id', 'name']);

        $this->title = $this->post->title;
        $this->category_id = $this->post->category_id;
        $this->tags = $this->post->tags_json;
        $this->previewUrl = $this->post->preview_url;
        $this->body = $this->post->body;
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

    public function update()
    {
        $this->authorize('update', $this->post);

        $validator = Validator::make(
            [
                'title' => $this->title,
                'category_id' => $this->category_id,
                // validate body text character count
                'body' => preg_replace('/[\r\n]/u', '', strip_tags($this->body)),
            ],
            [
                'title' => ['required', 'min:4', 'max:50'],
                'category_id' => ['required', 'numeric', 'exists:categories,id'],
                'body' => ['required', 'min:500', 'max:20000'],
            ],
            [
                'title.required' => '請填寫標題',
                'title.min' => '標題至少 4 個字元',
                'title.max' => '標題至多 50 個字元',
                'category_id.required' => '請選擇文章分類',
                'category_id.numeric' => '分類資料錯誤',
                'category_id.exists' => '分類不存在',
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

        $body = $postService->htmlPurifier($this->body);

        $this->post->title = $this->title;
        $this->post->slug = $postService->makeSlug($this->title);
        $this->post->category_id = $this->category_id;
        $this->post->body = $body;
        $this->post->excerpt = $postService->makeExcerpt($body);

        // upload photo
        if ($this->photo) {
            $imageName = FileService::generateImageFileName($this->photo);
            $uploadFilePath = $this->photo->storeAs('preview', $imageName, 's3');
            $previewUrl = Storage::disk('s3')->url($uploadFilePath);
        }

        $this->post->preview_url = $previewUrl ?? $this->previewUrl;
        $this->post->save();

        $tagIdsArray = $formatTransferService->tagsJsonToTagIdsArray($this->tags);

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
