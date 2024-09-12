<?php

namespace App\Livewire\Pages\Users;

use App\Mail\DestroyUser;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;
use Livewire\Attributes\Title;
use Livewire\Component;

class DestroyUserPage extends Component
{
    use AuthorizesRequests;

    public User $user;

    public function mount(User $user): void
    {
        $this->user = $user;
    }

    public function sendDestroyEmail(): void
    {
        $this->authorize('update', $this->user);

        // 生成一次性連結，並設定 5 分鐘後失效
        $destroyUserLink = URL::temporarySignedRoute(
            'users.destroy-confirmation',
            now()->addMinutes(5),
            ['user' => $this->user->id]
        );

        Mail::to($this->user)->queue(new DestroyUser($destroyUserLink));

        $this->dispatch('info-badge', status: 'success', message: '已寄出信件！');
    }

    #[Title('會員中心 - 刪除帳號')]
    public function render(): View
    {
        $this->authorize('update', $this->user);

        return view('livewire.pages.users.destroy-user-page');
    }
}
