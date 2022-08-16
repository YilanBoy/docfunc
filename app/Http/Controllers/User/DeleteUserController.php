<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class DeleteUserController extends Controller
{
    /**
     * 用戶刪除頁面
     *
     * @param  User  $user
     * @return Application|Factory|View
     *
     * @throws AuthorizationException
     */
    public function index(User $user)
    {
        $this->authorize('update', $user);

        return view('users.edit.delete', ['user' => $user]);
    }
}
