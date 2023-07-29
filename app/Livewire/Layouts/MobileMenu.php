<?php

namespace App\Livewire\Layouts;

use Livewire\Component;

class MobileMenu extends Component
{
    public $categories;

    public $showRegisterButton;

    public function render()
    {
        return view('livewire.layouts.mobile-menu');
    }
}
