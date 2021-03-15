<?php

namespace App\Http\View\Composers;

use App\Models\Category;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;

class CategoryComposer
{
    // 設定用來依賴注入的變數
    protected $category;

    public function __construct(Category $category)
    {
        // 將 Category Model 依賴注入到 CategoryComposer
        $this->category = $category;
    }

    public function compose(View $view)
    {
        // 因為分類不常調整，這裡使用快取減少對資料庫的讀取，快取時效性設定 1 天
        $categories = Cache::remember('categories', now()->addDay(), function () {
            return $this->category->all();
        });
        // 取得所有分類並放入變數 categories
        $view->with('categories', $categories);
    }
}
