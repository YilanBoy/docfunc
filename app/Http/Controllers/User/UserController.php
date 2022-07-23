<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
    public function index(User $user)
    {
        return view('users.index', ['user' => $user]);
    }

    /**
     * 編輯用戶個人資料
     *
     * @param User $user
     * @return Application|Factory|View
     * @throws AuthorizationException
     */
    public function edit(User $user)
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
    public function update(UserRequest $request, User $user)
    {
        $this->authorize('update', $user);

        // 更新會員資料
        $user->update([
            'name' => $request->name,
            'introduction' => $request->introduction,
        ]);

        return to_route('users.index', ['user' => $user->id])
            ->with('alert', ['status' => 'success', 'message' => '個人資料更新成功！']);
    }

    /**
     * 刪除用戶帳號
     *
     * @param Request $request
     * @param User $user
     * @return RedirectResponse
     */
    public function destroy(Request $request, User $user)
    {
        // 確認網址是否有效
        abort_if(!$request->hasValidSignature(), 401);

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $user->delete();

        return to_route('posts.index')
            ->with('alert', ['status' => 'success', 'message' => '帳號已刪除！']);
    }
}
