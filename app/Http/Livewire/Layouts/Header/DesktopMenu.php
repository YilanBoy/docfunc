<?php

namespace App\Http\Livewire\Layouts\Header;

use Livewire\Component;

class DesktopMenu extends Component
{

    public $categories;

    public $showRegisterButton;

    public function render()
    {
        return view('livewire.layouts.header.desktop-menu');
    }
}
