<?php

namespace App\Http\Livewire\UserInfoPage;

use App\Models\User;
use Livewire\Component;

class Index extends Component
{
    public User $user;

    public function mount(User $user)
    {
        $this->user = $user;
    }

    public function render()
    {
        return view('livewire.user-info-page.index');
    }
}
