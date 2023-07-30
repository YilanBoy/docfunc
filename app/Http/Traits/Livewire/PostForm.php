<?php

namespace App\Http\Traits\Livewire;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

trait PostForm
{
    public string $autoSaveKey = '';

    public int $category_id = 1;

    public ?string $preview_url = null;

    public $image = null;

    public bool $is_private = false;

    public string $title = '';

    public string $tags = '';

    public string $body = '';

    public function updatedImage(): void
    {
        $this->validateImage();

        $this->resetValidation('image');
    }

    // when data update, auto save it to redis
    public function updated(): void
    {
        $this->autoSave($this->autoSaveKey);
    }

    public function validateImage(): void
    {
        Validator::make(
            ['image' => $this->image],
            [
                'image' => ['nullable', 'image', 'max:1024'], // 1MB Max
            ],
            [
                'image.image' => '圖片格式有誤',
                'image.max' => '圖片大小不能超過 1024 KB',
            ]
        )->validate();
    }

    public function validatePost(): void
    {
        Validator::make(
            [
                'title' => $this->title,
                'category_id' => $this->category_id,
                'image' => $this->image,
                // validate body text character count
                'body' => preg_replace('/[\r\n]/u', '', strip_tags($this->body)),
            ],
            [
                'title' => ['required', 'min:4', 'max:50'],
                'category_id' => ['required', 'numeric', 'exists:categories,id'],
                'image' => ['nullable', 'image', 'max:1024'],
                'body' => ['required', 'min:500', 'max:20000'],
            ],
            [
                'title.required' => '請填寫標題',
                'title.min' => '標題至少 4 個字元',
                'title.max' => '標題至多 50 個字元',
                'category_id.required' => '請選擇文章分類',
                'category_id.numeric' => '分類資料錯誤',
                'category_id.exists' => '分類不存在',
                'image.image' => '圖片格式有誤',
                'image.max' => '圖片大小不能超過 1024 KB',
                'body.required' => '請填寫文章內容',
                'body.min' => '文章內容至少 500 個字元',
                'body.max' => '文章內容字數已超過 20,000 個字元',
            ]
        )->validate();
    }

    public function autoSave(string $key): void
    {
        Cache::put(
            $key,
            json_encode([
                'category_id' => $this->category_id,
                'is_private' => $this->is_private,
                'title' => $this->title,
                'tags' => $this->tags,
                'body' => $this->body,
            ], JSON_UNESCAPED_UNICODE),
            now()->addDays(7)
        );
    }

    public function setDataFromAutoSave(string $key): bool
    {
        if (Cache::has($key)) {
            $autoSavePostData = json_decode(Cache::get($key), true);

            $this->category_id = (int) $autoSavePostData['category_id'];
            $this->is_private = $autoSavePostData['is_private'];
            $this->title = $autoSavePostData['title'];
            $this->tags = $autoSavePostData['tags'];
            $this->body = $autoSavePostData['body'];

            return true;
        }

        return false;
    }

    public function clearAutoSave(string $key): void
    {
        Cache::forget($key);
    }
}
