<?php

namespace App\Http\Livewire\Users\Edit;

use App\Mail\DestroyUser;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Livewire\Component;

class DeleteUser extends Component
{
    use AuthorizesRequests;

    public User $user;

    public function mount(User $user)
    {
        $this->user = $user;
    }

    public function sendDestroyEmail()
    {
        $this->authorize('update', $this->user);

        // 生成一次性連結，並設定 5 分鐘後失效
        $destroyUserLink = URL::temporarySignedRoute(
            'users.destroy',
            now()->addMinutes(5),
            ['user' => $this->user->id]
        );

        Mail::to($this->user)->queue(new DestroyUser($destroyUserLink));

        $this->dispatchBrowserEvent('info-badge', ['status' => 'success', 'message' => '已寄出信件！']);
    }

    public function render()
    {
        $this->authorize('update', $this->user);

        return view('livewire.users.edit.delete-user');
    }
}
