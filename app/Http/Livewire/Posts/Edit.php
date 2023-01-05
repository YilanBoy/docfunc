<?php

namespace App\Http\Livewire\Posts;

use Livewire\Component;

class Edit extends Component
{
    public $id;

    public function mount(int $id)
    {
        $this->id = $id;
    }

    public function render()
    {
        return view('livewire.posts.edit');
    }
}
