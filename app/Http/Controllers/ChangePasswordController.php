<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Rules\MatchOldPassword;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ChangePasswordController extends Controller
{
    //
    /**
     * 用戶更新密碼頁面
     *
     * @param User $user
     * @param UserController $userController
     * @return Application|Factory|View
     * @throws AuthorizationException
     */
    public function edit(User $user, UserController $userController): Application|Factory|View
    {
        $userController->authorize('update', $user);

        return view('users.edit.change-password', ['user' => $user]);
    }

    /**
     * 用戶送出更新密碼
     *
     * @param Request $request
     * @param User $user
     * @param UserController $userController
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function update(Request $request, User $user, UserController $userController): RedirectResponse
    {
        $userController->authorize('update', $user);

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
}
