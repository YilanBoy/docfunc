<?php

namespace App\Livewire\Pages\Notifications;

use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    #[Title('我的通知')]
    public function render()
    {
        $notifications = auth()->user()->notifications()->paginate(20);

        auth()->user()->unreadNotifications->markAsRead();

        return view('livewire.pages.notifications.index', compact('notifications'));
    }
}
