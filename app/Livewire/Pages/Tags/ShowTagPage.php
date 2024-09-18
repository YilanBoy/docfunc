<?php

namespace App\Livewire\Pages\Tags;

use App\Models\Tag;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class ShowTagPage extends Component
{
    #[Locked]
    public Tag $tag;

    public function render(): View
    {
        return view('livewire.pages.tags.show-tag-page')
            ->title($this->tag->name);
    }
}
