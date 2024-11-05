<?php

namespace App\Livewire\Pages\Tags;

use App\Models\Tag;
use Illuminate\View\View;
use Livewire\Component;

class ShowTagPage extends Component
{
    public Tag $tag;

    public function mount(int $id): void
    {
        $this->tag = Tag::findOrFail($id);
    }

    public function render(): View
    {
        return view('livewire.pages.tags.show-tag-page')
            ->title($this->tag->name);
    }
}
