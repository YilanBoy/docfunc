<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;

class PostController extends Controller
{
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
