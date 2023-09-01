<?php

namespace App\Livewire\Shared\Layouts;

use Livewire\Component;

class MobileHeaderMenu extends Component
{
    public $categories;

    public $showRegisterButton;

    public function render()
    {
        return view('livewire.shared.layouts.mobile-header-menu');
    }
}
