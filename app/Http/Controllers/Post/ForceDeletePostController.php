<?php

namespace App\Http\Controllers\Post;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;

class ForceDeletePostController extends Controller
{
    /**
     * 完全刪除文章
     *
     * @param int $id 文章的 ID
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function destroy(int $id)
    {
        $softDeletedPost = Post::withTrashed()->find($id);

        $this->authorize('destroy', $softDeletedPost);

        $softDeletedPost->forceDelete();

        return to_route('users.index', ['user' => auth()->id(), 'tab' => 'posts'])
            ->with('alert', ['status' => 'success', 'message' => '成功刪除文章！']);
    }
}
