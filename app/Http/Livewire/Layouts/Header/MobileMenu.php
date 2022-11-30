<?php

namespace App\Http\Livewire\Layouts\Header;

use Livewire\Component;

class MobileMenu extends Component
{
    public $categories;

    public $showRegisterButton;

    public function render()
    {
        return view('livewire.layouts.header.mobile-menu');
    }
}
