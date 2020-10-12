<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;

class CategoryController extends Controller
{
    protected $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    public function show(Category $category, Request $request)
    {
        // URL 修正，使用帶標籤名稱的網址
        if (!empty($category->name) && $category->name != $request->name) {
            return redirect($category->linkWithName(), 301);
        }

        // 讀取分類 ID 關聯的話題，並按每 10 條分頁
        $posts = $this->post->withOrder($request->order)
            ->where('category_id', $category->id)
            ->with('user', 'category') // 預加載防止 N+1 問題
            ->paginate(10);

        // 傳參變量文章和分類到模板中
        return view('posts.index', ['posts' => $posts, 'category' => $category]);
    }
}
