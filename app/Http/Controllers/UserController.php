<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Category;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // 建構子
    public function __construct()
    {
        // 除了 index，其他 function 必須是登入的狀態
        $this->middleware('auth', ['except' => ['index']]);
    }

    /**
     * 用戶個人頁面
     *
     * @param User $user
     * @return Application|Factory|View
     */
    public function index(User $user): Application|Factory|View
    {
        $categories = Category::with(['posts' => function ($query) use ($user) {
            $query->where('user_id', $user->id);
        }])->get();

        $user->loadCount(['posts as posts_count_in_this_year' => function ($query) {
            $query->whereYear('created_at', date('Y'));
        }]);

        return view('users.index', ['user' => $user, 'categories' => $categories]);
    }

    /**
     * 編輯用戶個人資料
     *
     * @param User $user
     * @return Application|Factory|View
     * @throws AuthorizationException
     */
    public function edit(User $user): Application|Factory|View
    {
        // 會員只能進入自己的頁面，規則寫在 UserPolicy
        $this->authorize('update', $user);

        return view('users.edit.edit', ['user' => $user]);
    }

    /**
     * 更新用戶個人資料
     *
     * @param UserRequest $request
     * @param User $user
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function update(UserRequest $request, User $user): RedirectResponse
    {
        $this->authorize('update', $user);

        // 更新會員資料
        $user->update([
            'name' => $request->name,
            'introduction' => $request->introduction,
        ]);

        return redirect()
            ->route('users.index', ['user' => $user->id])
            ->with('success', '個人資料更新成功！');
    }

    /**
     * 用戶刪除頁面
     *
     * @param User $user
     * @return Application|Factory|View
     * @throws AuthorizationException
     */
    public function delete(User $user): Application|Factory|View
    {
        $this->authorize('update', $user);

        return view('users.edit.delete', ['user' => $user]);
    }

    /**
     * 刪除用戶帳號
     *
     * @param Request $request
     * @param User $user
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function destroy(Request $request, User $user): RedirectResponse
    {
        // 確認網址是否有效
        if (!$request->hasValidSignature()) {
            abort(401);
        }

        $this->authorize('update', $user);

        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $user->delete();

        return redirect()->route('posts.index')->with('success', '帳號已刪除！');
    }
}
