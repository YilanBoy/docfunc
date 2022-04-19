<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Mail\DestroyUser;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class SendDestroyUserEmailController extends Controller
{
    /**
     * 寄出刪除帳號的信件
     *
     * @param User $user
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function store(User $user)
    {
        $this->authorize('update', $user);

        // 生成一次性連結，並設定 5 分鐘後失效
        $destroyUserLink = URL::temporarySignedRoute(
            'users.destroy',
            now()->addMinutes(5),
            ['user' => $user->id]
        );

        Mail::to($user)->queue(new DestroyUser($destroyUserLink));

        return back()->with('status', '已寄出信件！');
    }
}
