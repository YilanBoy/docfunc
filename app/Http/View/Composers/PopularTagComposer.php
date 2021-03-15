<?php

namespace App\Http\View\Composers;

use App\Models\Tag;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;

// Tag Input JSON Format
class PopularTagComposer
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
        $popularTags = Cache::remember('popularTags', now()->addDay(), function () {

            // 取出標籤使用次數前 20 名
            $tagsCount = $this->tag->withCount('posts')
                ->having('posts_count', '>', 0)
                ->orderByDesc('posts_count')
                ->limit(20)
                ->get();

            return $tagsCount;
        });

        // 將熱門標籤的資料放入變數 popularTags
        $view->with('popularTags', $popularTags);
    }
}
