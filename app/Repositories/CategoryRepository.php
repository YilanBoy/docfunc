<?php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository
{
    // 設定用來依賴注入的變數
    protected $category;

    public function __construct(Category $category)
    {
        // 將 Category Model 依賴注入到 CategoryComposer
        $this->category = $category;
    }
}
