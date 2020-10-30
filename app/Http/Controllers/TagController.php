<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;

class TagController extends Controller
{
    public function show(Tag $tag, Request $request)
    {
        // 讀取分類 ID 關聯的話題，並按每 10 條分頁
        $posts = $tag->posts()->withOrder($request->order)
            ->with('user', 'category', 'tags') // 預加載防止 N+1 問題
            ->paginate(10);

        // 傳參變量文章和分類到模板中
        return view('posts.index', ['posts' => $posts, 'tag' => $tag]);
    }
}
