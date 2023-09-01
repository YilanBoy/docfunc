<?php

namespace App\Livewire\Shared\Layouts;

use Livewire\Component;

class DesktopHeaderMenu extends Component
{
    public $categories;

    public $showRegisterButton;

    public function render()
    {
        return view('livewire.shared.layouts.desktop-header-menu');
    }
}
