<?php

namespace App\Livewire\Tags;

use App\Models\Tag;
use Livewire\Component;

class ShowTagPage extends Component
{
    public Tag $tag;

    public function mount(Tag $tag)
    {
        $this->tag = $tag;
    }

    public function render()
    {
        return view('livewire.tags.show-tag-page')->title($this->tag->name);
    }
}
