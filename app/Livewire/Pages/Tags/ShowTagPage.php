<?php

namespace App\Livewire\Pages\Tags;

use App\Models\Tag;
use Illuminate\View\View;
use Livewire\Component;

class ShowTagPage extends Component
{
    public Tag $tag;

    public function mount(Tag $tag): void
    {
        $this->tag = $tag;
    }

    public function render(): View
    {
        return view('livewire.pages.tags.show-tag-page')
            ->title($this->tag->name);
    }
}
