<?php

namespace App\Livewire\Pages\Users;

use App\Models\User;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class ShowUserPage extends Component
{
    #[Locked]
    public User $user;

    public function mount(User $user): void
    {
        $this->user = $user;
    }

    public function render(): View
    {
        return view('livewire.pages.users.show-user-page')
            ->title($this->user->name.' 的個人資訊');
    }
}
