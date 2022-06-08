<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;

class RestorePostController extends Controller
{
    /**
     * 恢復軟刪除的文章
     *
     * @param int $id 文章的 ID
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function update(int $id)
    {
        $softDeletedPost = Post::withTrashed()->find($id);

        $this->authorize('update', $softDeletedPost);

        $softDeletedPost->restore();

        return to_route('users.index', ['user' => auth()->id(), 'tab' => 'posts'])
            ->with('alert', ['icon' => 'success', 'title' => '成功恢復文章！']);
    }
}
