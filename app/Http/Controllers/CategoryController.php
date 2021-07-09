<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;

class CategoryController extends Controller
{
    public function show(Category $category, Request $request)
    {
        // URL 修正，使用帶標籤名稱的網址
        if ($category->name && $category->name !== $request->name) {
            return redirect($category->link_with_name, 301);
        }

        // 傳參變量文章和分類到模板中
        return view('posts.index', ['category' => $category]);
    }
}
