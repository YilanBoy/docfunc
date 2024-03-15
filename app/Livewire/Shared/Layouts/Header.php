<?php

namespace App\Livewire\Shared\Layouts;

use App\Livewire\Actions\Logout;
use App\Models\Category;
use App\Services\SettingService;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class Header extends Component
{
    public function logout(Logout $logout): void
    {
        $logout();

        $this->dispatch('info-badge', status: 'success', message: '已成功登出！');

        $this->redirect(route('login'), navigate: true);
    }

    public function render()
    {
        // Because categories are not frequently adjusted,
        // use cache to reduce database reads, cache expiration time is set to 1 day
        $categories = Cache::remember('categories', now()->addDay(), function () {
            return Category::all(['id', 'name', 'icon']);
        });

        // Whether to display the registration button
        $showRegisterButton = SettingService::isRegisterAllowed();

        return view(
            'livewire.shared.layouts.header',
            compact('categories', 'showRegisterButton')
        );
    }
}
