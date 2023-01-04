<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Route;

class PostController extends Controller
{
    /**
     * 文章首頁
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $pageTitle = (Route::currentRouteName() === 'root')
            ? config('app.name')
            : '所有文章';

        return view('posts.index', compact('pageTitle'));
    }

    /**
     * 文章內容
     *
     * @param  Request  $request
     * @param  Post  $post
     * @return Application|Factory|View|RedirectResponse|Redirector
     */
    public function show(Request $request, Post $post)
    {
        // URL 修正，使用帶 slug 的網址
        if ($post->slug && $post->slug !== $request->slug) {
            return redirect($post->link_with_slug, 301);
        }

        return view('posts.show', ['post' => $post])
            ->with('alert', ['status' => 'success', 'message' => '成功新增文章！']);
    }

    /**
     * 創建文章頁面
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * 文章編輯頁面
     *
     * @param  int  $id
     * @return Application|Factory|View
     */
    public function edit(int $id)
    {
        return view('posts.edit', compact('id'));
    }

    /**
     * 刪除文章 (軟刪除)
     *
     * @param  Post  $post
     * @return RedirectResponse
     *
     * @throws AuthorizationException
     */
    public function destroy(Post $post)
    {
        $this->authorize('destroy', $post);

        $post->delete();

        return to_route('users.index', ['user' => auth()->id(), 'tab' => 'posts'])
            ->with('alert', ['status' => 'success', 'message' => '成功刪除文章！']);
    }
}
