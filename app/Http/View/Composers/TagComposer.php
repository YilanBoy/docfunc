<?php

namespace App\Http\View\Composers;

use App\Models\Tag;
use Illuminate\View\View;
use Cache;

// Tag Input JSON Format
class TagComposer
{
    // 設定用來依賴注入的變數
    protected $tag;

    public function __construct(Tag $tag)
    {
        // 將 Tag Model 依賴注入到 TagComposer
        $this->tag = $tag;
    }

    public function compose(View $view)
    {
        // 這裡使用快取減少對資料庫的讀取，快取設定 86400 秒(一天)過期
        $inputTags = Cache::remember('inputTags', 86400, function () {
            $tagArray = [];
            foreach ($this->tag->all() as $tag) {
                array_push($tagArray, ['id' => $tag->id, 'value' => $tag->name]);
            }

            // 傳過去的格式會長這樣
            // [{"id":"2","value":"C#"},{"id":"5","value":"Dart"}]
            return json_encode($tagArray);
        });

        // 取得所有連結資料並放入變數 links
        $view->with('inputTags', $inputTags);
    }
}
