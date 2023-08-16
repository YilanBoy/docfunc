<?php

namespace App\Http\Livewire;

use App\Models\Tag;
use Livewire\Component;

class TagPage extends Component
{
    public Tag $tag;

    public function mount(Tag $tag)
    {
        $this->tag = $tag;
    }

    public function render()
    {
        return view('livewire.tag-page', [
            'title' => $this->tag->name,
        ]);
    }
}
