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
use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Auth;
use App\Jobs\SendDestroyUserEmail;

class UserController extends Controller
{
    // 建構子
    public function __construct()
    {
        // 除了 show function，其他 function 必須是登入的狀態
        $this->middleware('auth', ['except' => ['show']]);
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
            // 替換連續兩次以上空白與換行的混合
            'introduction' => preg_replace(
                '/(\s*(\\r\\n|\\r|\\n)\s*){2,}/u',
                PHP_EOL,
                $request->introduction
            ),
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
     * 寄出刪除帳號的信件
     *
     * @param User $user
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function sendDestroyEmail(User $user): RedirectResponse
    {
        $this->authorize('update', $user);

        SendDestroyUserEmail::dispatch($user);

        return back()->with('status', '已寄出信件！');
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
