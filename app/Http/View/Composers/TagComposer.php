<?php

namespace App\Http\View\Composers;

use App\Models\Tag;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;

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

            $tagsArray = $this->tag->all()->map(function ($tag) {
                return ['id' => $tag->id, 'value' => $tag->name];
            })->all();

            // 傳過去的格式會長這樣
            // [{"id":"2","value":"C#"},{"id":"5","value":"Dart"}]
            return json_encode($tagsArray, JSON_UNESCAPED_UNICODE);
        });

        $view->with('inputTags', $inputTags);
    }
}
