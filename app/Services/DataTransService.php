<?php

namespace App\Services;

class DataTransService
{
    // 將 tag 的 JSON 資料轉成 array，這裡的問號，代表允許參數為 null
    public function tagJsonToArray(?string $tagJson)
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
