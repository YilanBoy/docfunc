<?php

namespace App\Livewire\Pages\Users;

use App\Enums\UserInfoTab;
use App\Models\User;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Url;
use Livewire\Component;

class ShowUserPage extends Component
{
    #[Locked]
    public int $userId;

    #[Url(as: 'tab')]
    public string $tabSelected = UserInfoTab::INFORMATION->value;

    public function render(): View
    {
        $user = User::findOrFail($this->userId);

        return view('livewire.pages.users.show-user-page')
            ->title($user->name.' 的個人資訊');
    }
}
