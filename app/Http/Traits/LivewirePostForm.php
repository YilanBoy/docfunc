<?php

namespace App\Http\Traits;

use App\Models\Post;
use App\Services\FileService;
use App\Services\FormatTransferService;
use App\Services\PostService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

trait LivewirePostForm
{
    public function validatePhoto(): void
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

    public function validatePost(): void
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
    }

    public function createPost(): Post
    {
        // xss filter
        $body = PostService::htmlPurifier($this->body);

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
            'slug' => PostService::makeSlug($this->title),
            'preview_url' => $previewUrl,
            'excerpt' => PostService::makeExcerpt($body),
        ]);

        // convert tags json string to array
        $tagIdsArray = FormatTransferService::tagsJsonToTagIdsArray($this->tags);

        // create new tags relation with post in database
        $post->tags()->attach($tagIdsArray);

        return $post;
    }

    public function updatePost(): void
    {
        $body = PostService::htmlPurifier($this->body);

        $this->post->title = $this->title;
        $this->post->slug = PostService::makeSlug($this->title);
        $this->post->category_id = $this->category_id;
        $this->post->body = $body;
        $this->post->excerpt = PostService::makeExcerpt($body);

        // upload photo
        if ($this->photo) {
            $imageName = FileService::generateImageFileName($this->photo);
            $uploadFilePath = $this->photo->storeAs('preview', $imageName, 's3');
            $previewUrl = Storage::disk('s3')->url($uploadFilePath);
        }

        $this->post->preview_url = $previewUrl ?? $this->previewUrl;
        $this->post->save();

        $tagIdsArray = FormatTransferService::tagsJsonToTagIdsArray($this->tags);

        $this->post->tags()->sync($tagIdsArray);
    }
}
