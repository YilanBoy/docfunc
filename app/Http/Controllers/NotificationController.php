<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // 獲取登入會員的所有通知
        $notifications = Auth::user()->notifications()->paginate(20);

        // 標記為已讀，未讀數量歸零
        Auth::user()->markAsRead();

        return view('notifications.index', ['notifications' => $notifications]);
    }
}
