<?php

namespace App\Services;

class DataTransService
{
    // 將 tag 的 JSON 資料轉成 array，這裡的問號，代表允許參數為 null
    // 後面的 : array 為聲明這個 function 返回的值為 array 類型
    public function tagJsonToArray(?string $tagJson): array
    {
        $tagArray = [];

        // 沒有設定標籤
        if (is_null($tagJson)) {
            return $tagArray;
        }

        $tags = json_decode($tagJson);
        // 使用 sync 同步關聯資料表的紀錄
        foreach ($tags as $tag) {
            array_push($tagArray, $tag->id);
        }

        return $tagArray;
    }
}
