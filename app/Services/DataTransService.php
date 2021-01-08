<?php

namespace App\Services;

class DataTransService
{
    // 將 tag 的 JSON 資料轉成 array，這裡的問號，代表允許參數為 null
    // 後面的 : array 為聲明這個 function 返回的值為 array 類型
    public function tagJsonToArray(?string $tagJson): array
    {
        // 沒有設定標籤
        if (is_null($tagJson)) {
            return [];
        }

        $tags = json_decode($tagJson);

        // 生成由 tag ID 組成的 Array
        $tagArray = collect($tags)->map(function ($tag) {
            return $tag->id;
        })->all();

        return $tagArray;
    }
}
