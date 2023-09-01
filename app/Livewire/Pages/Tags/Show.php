<?php

namespace App\Livewire\Pages\Tags;

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
        return view('livewire.pages.tags.show')
            ->title($this->tag->name);
    }
}
