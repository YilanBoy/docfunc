<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;

class TagController extends Controller
{
    public function show(Tag $tag, Request $request)
    {
        // 傳參變量文章和分類到模板中
        return view('posts.index', ['tag' => $tag]);
    }
}
