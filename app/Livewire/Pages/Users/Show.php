<?php

namespace App\Livewire\Pages\Users;

use App\Models\User;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Show extends Component
{
    #[Locked]
    public User $user;

    #[Locked]
    public array $tabs = [
        ['value' => 'information', 'text' => '個人資訊'],
        ['value' => 'posts', 'text' => '發布文章'],
        ['value' => 'comments', 'text' => '留言紀錄'],
    ];

    public function mount(User $user): void
    {
        $this->user = $user;
    }

    public function render()
    {
        return view('livewire.pages.users.show')
            ->title($this->user->name.' 的個人資訊');
    }
}
