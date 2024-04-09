<?php

namespace App\Livewire\Shared\Layouts;

use Illuminate\View\View;
use Livewire\Component;

class MobileHeaderMenu extends Component
{
    public $categories;

    public $showRegisterButton;

    public function render(): View
    {
        return view('livewire.shared.layouts.mobile-header-menu');
    }
}
