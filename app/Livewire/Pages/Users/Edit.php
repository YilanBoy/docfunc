<?php

namespace App\Livewire\Pages\Users;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Attributes\Title;
use Livewire\Component;

class Edit extends Component
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

    public function mount(User $user): void
    {
        $this->user = $user;
        $this->name = $user->name;
        $this->introduction = $user->introduction;
    }

    public function update(): void
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
    public function render(): View
    {
        // 會員只能進入自己的頁面，規則寫在 UserPolicy
        $this->authorize('update', $this->user);

        return view('livewire.pages.users.edit');
    }
}
