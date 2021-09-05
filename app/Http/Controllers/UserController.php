<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Category;
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

    // 個人頁面
    public function show(User $user)
    {
        $categories = Category::with(['posts' => function ($query) use ($user) {
            $query->where('user_id', $user->id);
        }])->get();

        return view('users.show', ['user' => $user, 'categories' => $categories]);
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
            'introduction' => preg_replace(
                '/(\s*(\\r\\n|\\r|\\n)\s*){2,}/u',
                PHP_EOL,
                $request->introduction
            ),
        ]);

        return redirect()
            ->route('users.show', ['user' => $user->id])
            ->with('success', '個人資料更新成功！');
    }

    // 更新密碼頁面
    public function changePassword(User $user)
    {
        $this->authorize('update', $user);

        return view('users.change-password', ['user' => $user]);
    }

    // 更新密碼
    public function updatePassword(Request $request, User $user)
    {
        $this->authorize('update', $user);

        $passwordRule = Password::min(8)->letters()->mixedCase()->numbers();

        $rules = [
            'current_password' => ['required', new MatchOldPassword()],
            'new_password' => ['required', 'confirmed', $passwordRule],
        ];

        $messages = [
            'current_password.required' => '請輸入現在的密碼',
            'new_password.required' => '請輸入新密碼',
            'new_password.confirmed' => '新密碼與確認新密碼不符合',
        ];

        $request->validate($rules, $messages);

        User::find(auth()->id())->update(
            ['password' => Hash::make($request->new_password)]
        );

        return back()->with('status', '密碼修改成功！');
    }

    // 刪除用戶頁面
    public function delete(User $user)
    {
        $this->authorize('update', $user);

        return view('users.delete', ['user' => $user]);
    }

    // 寄出刪除帳號的信件
    public function sendDestroyEmail(User $user)
    {
        $this->authorize('update', $user);

        SendDestroyUserEmail::dispatch($user);

        return back()->with('status', '已寄出信件！');
    }

    // 刪除用戶帳號
    public function destroy(Request $request, User $user)
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
