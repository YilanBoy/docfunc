<?php

namespace App\Livewire\Pages\Users;

use App\Models\User;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Show extends Component
{
    #[Locked]
    public User $user;

    #[Locked]
    public array $tabs = [
        ['value' => 'information', 'text' => '個人資訊', 'component' => 'shared.users.info-cards'],
        ['value' => 'posts', 'text' => '發布文章', 'component' => 'shared.users.posts'],
        ['value' => 'comments', 'text' => '留言紀錄', 'component' => 'shared.users.comments'],
    ];

    public function mount(User $user): void
    {
        $this->user = $user;
    }

    public function render(): View
    {
        return view('livewire.pages.users.show')
            ->title($this->user->name.' 的個人資訊');
    }
}
