<?php

namespace App\Services;

use Illuminate\Support\Str;

class PostService
{
    // 生成 Slug
    public function makeSlug(string $title)
    {
        // 去掉特殊字元，只留中文與英文
        $title = preg_replace('/[^A-Za-z0-9 \p{Han}]+/u', '', $title);

        // 去掉空白
        $title = str_replace(' ', '-', $title);

        // 後面加個 '-post' 是為了避免 slug 只有 'edit' 時，會與編輯頁面的路由發生衝突
        return $title . '-post';
    }

    // 生成摘錄的方法
    public function make_excerpt($value, $length = 200)
    {
        $excerpt = trim(preg_replace('/\r\n|\r|\n+/', ' ', strip_tags($value)));
        return Str::limit($excerpt, $length);
    }
}
