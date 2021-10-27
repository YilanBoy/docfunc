<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use App\Models\Tag;

class TagController extends Controller
{
    /**
     * 特定標籤的文章列表
     *
     * @param Tag $tag
     * @return Application|Factory|View
     */
    public function show(Tag $tag): Application|Factory|View
    {
        // 傳參變量文章和分類到模板中
        return view('posts.index', ['tag' => $tag]);
    }
}
