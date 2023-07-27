<?php

namespace App\Http\Traits\Livewire;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

trait PostForm
{
    public array $post = [
        'category_id' => 1,
        'preview_url' => null,
        'image' => null,
        'is_private' => false,
        'title' => '',
        'tags' => '',
        'body' => '',
    ];

    public function validateImage(): void
    {
        $validator = Validator::make(
            ['image' => $this->post['image']],
            [
                'image' => ['nullable', 'image', 'max:1024'], // 1MB Max
            ],
            [
                'image.image' => '圖片格式有誤',
                'image.max' => '圖片大小不能超過 1024 KB',
            ]
        );

        if ($validator->fails()) {
            $this->dispatchBrowserEvent('scroll-to-top');
        }

        $validator->validate();
    }

    public function updatedPostImage(): void
    {
        $this->validateImage();

        $this->resetValidation('image');
    }

    public function validatePost(): void
    {
        $validator = Validator::make(
            [
                'title' => $this->post['title'],
                'category_id' => $this->post['category_id'],
                'image' => $this->post['image'],
                // validate body text character count
                'body' => preg_replace('/[\r\n]/u', '', strip_tags($this->post['body'])),
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
        );

        if ($validator->fails()) {
            $this->dispatchBrowserEvent('scroll-to-top');
        }

        $validator->validate();
    }

    public function autoSave(string $key): void
    {
        Cache::put(
            $key,
            json_encode([
                'category_id' => $this->post['category_id'],
                'is_private' => $this->post['is_private'],
                'title' => $this->post['title'],
                'tags' => $this->post['tags'],
                'body' => $this->post['body'],
            ], JSON_UNESCAPED_UNICODE),
            now()->addDays(7)
        );
    }

    public function getDataFromAutoSave(string $key): bool
    {
        if (Cache::has($key)) {
            $autoSavePostData = json_decode(Cache::get($key), true);

            $this->post['category_id'] = (int) $autoSavePostData['category_id'];
            $this->post['is_private'] = $autoSavePostData['is_private'];
            $this->post['title'] = $autoSavePostData['title'];
            $this->post['tags'] = $autoSavePostData['tags'];
            $this->post['body'] = $autoSavePostData['body'];

            return true;
        }

        return false;
    }

    public function clearAutoSave(string $key): void
    {
        Cache::forget($key);
    }
}
