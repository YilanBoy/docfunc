<?php

namespace App\Livewire;

use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class NotificationPage extends Component
{
    use WithPagination;

    #[Title('我的通知')]
    public function render()
    {
        // 獲取登入會員的所有通知
        $notifications = auth()->user()->notifications()->paginate(20);

        // 標記為已讀，未讀數量歸零
        auth()->user()->markAsRead();

        return view('livewire.notification-page', compact('notifications'));
    }
}
