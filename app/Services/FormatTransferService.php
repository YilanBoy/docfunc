<?php

namespace App\Services;

class FormatTransferService
{
    // 將 tag 的 JSON 資料轉成 array，這裡的問號，代表允許參數為 null
    // 後面的 : array 為聲明這個 function 返回的值為 array 類型
    public function tagsJsonToTagIdsArray(?string $tagsJson): array
    {
        // 沒有設定標籤
        if (is_null($tagsJson)) {
            return [];
        }

        $tags = json_decode($tagsJson);

        // 生成由 tag ID 組成的 Array
        $tagIdsArray = collect($tags)
            ->map(fn ($tag) => $tag->id)
            ->all();

        return $tagIdsArray;
    }
}
