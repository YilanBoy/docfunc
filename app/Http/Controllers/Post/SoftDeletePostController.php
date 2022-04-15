<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;

class SoftDeletePostController extends Controller
{
    /**
     * 軟刪除文章
     *
     * @param Post $post
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function destroy(Post $post)
    {
        $this->authorize('destroy', $post);

        $post->delete();

        return redirect()
            ->route('users.index', ['user' => auth()->id(), 'tab' => 'posts'])
            ->with('alert', ['icon' => 'success', 'title' => '成功標記文章為刪除狀態！']);
    }
}
