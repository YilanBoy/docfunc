<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;

class NotificationPage extends Component
{
    use WithPagination;

    public function render()
    {
        // 獲取登入會員的所有通知
        $notifications = auth()->user()->notifications()->paginate(20);

        // 標記為已讀，未讀數量歸零
        auth()->user()->markAsRead();

        return view('livewire.notification-page', compact('notifications'));
    }
}
