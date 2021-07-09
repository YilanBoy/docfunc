<?php

namespace App\Http\View\Composers;

use App\Models\Tag;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;

// Tag Input JSON Format
class PopularTagComposer
{
    public function compose(View $view)
    {
        $popularTags = Cache::remember('popularTags', now()->addDay(), function () {

            // 取出標籤使用次數前 20 名
            return Tag::withCount('posts')
                ->having('posts_count', '>', 0)
                ->orderByDesc('posts_count')
                ->limit(20)
                ->get();
        });

        // 將熱門標籤的資料放入變數 popularTags
        $view->with('popularTags', $popularTags);
    }
}
