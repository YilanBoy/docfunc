<?php

namespace App\Livewire\Users\Information;

use App\Models\User;
use Livewire\Component;

class UserInformationPage extends Component
{
    public User $user;

    public function mount(User $user)
    {
        $this->user = $user;
    }

    public function render()
    {
        return view('livewire.users.information.user-information-page')
            ->title($this->user->name.' 的個人資訊');
    }
}
