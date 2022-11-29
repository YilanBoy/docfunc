<?php

namespace App\Http\Livewire\Layouts\Header;

use App\Models\Category;
use App\Services\SettingService;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class Nav extends Component
{
    public function render()
    {
        return view('livewire.layouts.header.nav');
    }
}
