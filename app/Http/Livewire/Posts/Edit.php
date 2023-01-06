<?php

namespace App\Http\Livewire\Posts;

use Livewire\Component;

class Edit extends Component
{
    // we can't use id for naming property, it's livewire internal usage
    public $postId;

    public function mount(int $id)
    {
        $this->postId = $id;
    }

    public function render()
    {
        return view('livewire.posts.edit');
    }
}
