<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use Auth;
use App\Services\PostService;
use App\Services\DataTransService;

class PostController extends Controller
{
    protected $post, $category, $postService, $dataTransService;

    public function __construct(
        Post $post,
        Category $category,
        PostService $postService,
        DataTransService $dataTransService
    ) {
        $this->post = $post;
        $this->category = $category;
        $this->postService = $postService;
        $this->dataTransService = $dataTransService;
    }

    // 文章列表
    public function index(Request $request)
    {
        $posts = $this->post->withOrder($request->order)
            ->with('user', 'category', 'tags') // 預加載防止 N+1 問題
            ->paginate(10);

        return view('posts.index', ['posts' => $posts]);
    }

    // 文章內容
    public function show(Request $request, Post $post)
    {
        // URL 修正，使用帶 slug 的網址
        if (!empty($post->slug) && $post->slug !== $request->slug) {
            return redirect($post->linkWithSlug(), 301);
        }

        return view('posts.show', ['post' => $post]);
    }

    // 新增文章頁面
    public function create()
    {
        return view('posts.create');
    }

    // 新增文章
    public function store(PostRequest $request)
    {
        $this->post->fill($request->validated());
        $this->post->user_id = Auth::id();
        $this->post->slug = $this->postService->makeSlug($request->title);
        $this->post->save();

        // 將傳過來的 JSON 資料轉成 array
        $tagArray = $this->dataTransService->tagJsonToArray($request->tags);

        // 在關聯表新增關聯
        $this->post->tags()->attach($tagArray);

        return redirect()->to($this->post->linkWithSlug())->with('success', '成功新增文章！');
    }

    // 文章編輯頁面
    public function edit(Post $post)
    {
        // 只能編輯自己發佈的文章，規則寫在 PostPolicy
        $this->authorize('update', $post);

        // 將文章的 tag 資料撈出，並轉成 tagify 可以吃的 JSON 格式
        $tagArray = [];
        foreach ($post->tags as $tag) {
            array_push($tagArray, ['id' => $tag->id, 'value' => $tag->name]);
        }
        // 傳過去的格式會長這樣
        // [{"id":"2","value":"C#"},{"id":"5","value":"Dart"}]
        $post->tags = json_encode($tagArray);

        return view('posts.edit', ['post' => $post]);
    }

    // 更新文章
    public function update(PostRequest $request, Post $post)
    {
        $this->authorize('update', $post);

        $post->slug = $this->postService->makeSlug($request->title);
        $post->update($request->validated());

        $tagArray = $this->dataTransService->tagJsonToArray($request->tags);

        // 關聯表更新
        $post->tags()->sync($tagArray);

        return redirect()->to($post->linkWithSlug())->with('success', '成功更新文章！');
    }

    // 刪除文章
    public function destroy(Post $post)
    {
        $this->authorize('destroy', $post);

        $post->delete();

        return redirect()->route('posts.index')->with('success', '成功刪除文章！');
    }
}
