<?php

namespace App\Http\Livewire\Tags;

use App\Models\Tag;
use Livewire\Component;

class Show extends Component
{
    public Tag $tag;

    public function mount(Tag $tag)
    {
        $this->tag = $tag;
    }

    public function render()
    {
        return view('livewire.tags.show', [
            'title' => $this->tag->name,
        ]);
    }
}
