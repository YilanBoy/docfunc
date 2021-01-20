<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        // 頁面顯示 users/show.blade.php，並傳入 user 參數
        return view('users.show', ['user' => $user]);
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

        // 取得 POST 的參數，validated 會根據 UserRequest 的規則取得對應的參數
        // 更新會員資料
        $user->update($request->validated());

        return redirect()->route('users.show', ['user' => $user->id])->with('success', '個人資料更新成功！');
    }
}
