<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class CategoryController extends Controller
{
    /**
     * @param Category $category
     * @param Request $request
     * @return Application|Factory|View|RedirectResponse|Redirector
     */
    public function show(Category $category, Request $request)
    {
        // URL 修正，使用帶標籤名稱的網址
        if ($category->name && $category->name !== $request->name) {
            return redirect($category->link_with_name, 301);
        }

        $pageTitle = $category->name;

        // 傳參變量文章和分類到模板中
        return view('posts.index', compact('category', 'pageTitle'));
    }
}
