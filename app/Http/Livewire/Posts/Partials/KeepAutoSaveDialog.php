<?php

namespace App\Http\Livewire\Posts\Partials;

use Livewire\Component;

class KeepAutoSaveDialog extends Component
{
    public bool $showDialog;

    public function render()
    {
        return view('livewire.posts.partials.keep-auto-save-dialog');
    }
}
