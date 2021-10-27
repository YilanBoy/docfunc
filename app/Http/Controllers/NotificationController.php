<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 通知列表首頁
     *
     * @return Application|Factory|View
     */
    public function index(): Application|Factory|View
    {
        // 獲取登入會員的所有通知
        $notifications = auth()->user()->notifications()->paginate(20);

        // 標記為已讀，未讀數量歸零
        auth()->user()->markAsRead();

        return view('notifications.index', ['notifications' => $notifications]);
    }
}
