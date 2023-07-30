<?php

namespace App\Http\Livewire\Layouts;

use Livewire\Component;

class DesktopHeaderMenu extends Component
{
    public $categories;

    public $showRegisterButton;

    public function render()
    {
        return view('livewire.layouts.desktop-header-menu');
    }
}
