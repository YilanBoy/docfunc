<?php

namespace App\Livewire;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Title;
use Livewire\Component;

class EditUserInfoPage extends Component
{
    use AuthorizesRequests;

    public User $user;

    public string $name;

    public string $introduction;

    protected function rules(): array
    {
        return (new UserRequest())->rules();
    }

    protected function messages(): array
    {
        return (new UserRequest())->messages();
    }

    public function mount(User $user)
    {
        $this->user = $user;
        $this->name = $user->name;
        $this->introduction = $user->introduction;
    }

    public function update()
    {
        $this->authorize('update', $this->user);

        $this->validate();

        // 更新會員資料
        $this->user->update([
            'name' => $this->name,
            'introduction' => $this->introduction,
        ]);

        $this->dispatch('info-badge', status: 'success', message: '個人資料更新成功');
    }

    #[Title('會員中心 - 編輯個人資料')]
    public function render()
    {
        // 會員只能進入自己的頁面，規則寫在 UserPolicy
        $this->authorize('update', $this->user);

        return view('livewire.edit-user-info-page');
    }
}
