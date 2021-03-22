<?php

namespace App\Http\View\Composers;

use App\Models\Category;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;

class CategoryComposer
{
    public function compose(View $view)
    {
        // 因為分類不常調整，這裡使用快取減少對資料庫的讀取，快取時效性設定 1 天
        $categories = Cache::remember('categories', now()->addDay(), function () {
            return Category::all();
        });
        // 取得所有分類並放入變數 categories
        $view->with('categories', $categories);
    }
}
