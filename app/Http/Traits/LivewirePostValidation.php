<?php

namespace App\Http\Traits;

use App\Models\Post;
use App\Services\FileService;
use App\Services\FormatTransferService;
use App\Services\PostService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

trait LivewirePostValidation
{
    public function validateImage(): void
    {
        $validator = Validator::make(
            ['image' => $this->image],
            [
                'image' => ['image', 'max:1024'], // 1MB Max
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

    public function validatePost(): void
    {
        $validator = Validator::make(
            [
                'title' => $this->title,
                'categoryId' => $this->categoryId,
                'image' => $this->image,
                // validate body text character count
                'body' => preg_replace('/[\r\n]/u', '', strip_tags($this->body)),
            ],
            [
                'title' => ['required', 'min:4', 'max:50'],
                'categoryId' => ['required', 'numeric', 'exists:categories,id'],
                'image' => ['nullable', 'image', 'max:1024'],
                'body' => ['required', 'min:500', 'max:20000'],
            ],
            [
                'title.required' => '請填寫標題',
                'title.min' => '標題至少 4 個字元',
                'title.max' => '標題至多 50 個字元',
                'categoryId.required' => '請選擇文章分類',
                'categoryId.numeric' => '分類資料錯誤',
                'categoryId.exists' => '分類不存在',
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
}
