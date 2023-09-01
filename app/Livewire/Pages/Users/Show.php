<?php

namespace App\Livewire\Pages\Users;

use App\Models\User;
use Livewire\Component;

class Show extends Component
{
    public User $user;

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
