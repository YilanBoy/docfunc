<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\UserRequest;

class UserController extends Controller
{
    // 建構子
    public function __construct()
    {
        // 除了 show function，其他 function 必須是登入的狀態
        $this->middleware('auth', ['except' => ['show']]);
    }

    // 個人頁面
    public function show(User $user)
    {
        // 該會員的文章與留言
        $posts = $user->posts()->withTrashed()->with('category')->latest()->paginate(5);
        $replies = $user->replies()->whereHas('post', function ($query) {
            return $query->whereNull('deleted_at');
        })->with('post')->latest()->paginate(5);

        // 頁面顯示 users/show.blade.php，並傳入參數
        return view('users.show', [
            'user' => $user,
            'posts' => $posts,
            'replies' => $replies,
        ]);
    }

    // 編輯個人資料
    public function edit(User $user)
    {
        // 會員只能進入自己的頁面，規則寫在 UserPolicy
        $this->authorize('update', $user);

        return view('users.edit', ['user' => $user]);
    }

    // 更新個人資料
    public function update(UserRequest $request, User $user)
    {
        $this->authorize('update', $user);

        // 更新會員資料
        $user->update([
            'name' => $request->name,
            // 替換連續兩次以上空白與換行的混合
            'introduction' => preg_replace('/(\s*(\\r\\n|\\r|\\n)\s*){2,}/u', PHP_EOL, $request->introduction),
        ]);

        return redirect()->route('users.show', ['user' => $user->id])->with('success', '個人資料更新成功！');
    }
}
