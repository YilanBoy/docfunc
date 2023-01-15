<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DestroyUserController extends Controller
{
    /**
     * 刪除用戶帳號
     *
     * @param  Request  $request
     * @param  User  $user
     * @return RedirectResponse
     */
    public function __invoke(Request $request, User $user)
    {
        // 確認網址是否有效
        abort_if(! $request->hasValidSignature(), 401);

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        $user->delete();

        return to_route('posts.index')
            ->with('alert', ['status' => 'success', 'message' => '帳號已刪除！']);
    }
}
