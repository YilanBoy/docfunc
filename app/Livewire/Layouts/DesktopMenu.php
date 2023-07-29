<?php

namespace App\Livewire\Layouts;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DesktopMenu extends Component
{
    public $categories;

    /**
     * Destroy an authenticated session.
     */
    public function logout()
    {
        Auth::guard('web')->logout();

        session()->invalidate();

        session()->regenerateToken();

        $this->redirect('/login', navigate: true);

        $this->dispatch('info-badge', status: 'success', message: '已登出！');
    }

    public $showRegisterButton;

    public function render()
    {
        return view('livewire.layouts.desktop-menu');
    }
}
