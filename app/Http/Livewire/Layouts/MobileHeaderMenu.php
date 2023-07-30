<?php

namespace App\Http\Livewire\Layouts;

use Livewire\Component;

class MobileHeaderMenu extends Component
{
    public $categories;

    public $showRegisterButton;

    public function render()
    {
        return view('livewire.layouts.mobile-header-menu');
    }
}
