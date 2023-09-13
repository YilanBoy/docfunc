<?php

namespace App\Livewire\Pages\Users;

use App\Models\User;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Show extends Component
{
    public User $user;

    #[Locked]
    public array $tabs = [
        ['value' => 'information', 'text' => '個人資訊', 'icon' => 'bi bi-info-circle-fill'],
        ['value' => 'posts', 'text' => '發布文章', 'icon' => 'bi bi-file-earmark-post-fill'],
        ['value' => 'comments', 'text' => '留言紀錄', 'icon' => 'bi bi-chat-square-text-fill'],
    ];

    public function mount(User $user)
    {
        $this->user = $user;
    }

    public function render()
    {
        return view('livewire.pages.users.show')
            ->title($this->user->name.' 的個人資訊');
    }
}
