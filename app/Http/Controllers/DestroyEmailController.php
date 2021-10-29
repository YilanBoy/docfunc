<?php

namespace App\Http\Controllers;

use App\Jobs\SendDestroyUserEmail;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;

class DestroyEmailController extends Controller
{
    /**
     * 寄出刪除帳號的信件
     *
     * @param User $user
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function store(User $user): RedirectResponse
    {
        $this->authorize('update', $user);

        SendDestroyUserEmail::dispatch($user);

        return back()->with('status', '已寄出信件！');
    }
}
