<?php

namespace App\Livewire\Pages\Tags;

use App\Models\Tag;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class ShowTagPage extends Component
{
    #[Locked]
    public int $tagId;

    public function render(): View
    {
        $tag = Tag::findOrFail($this->tagId);

        return view(
            'livewire.pages.tags.show-tag-page',
            compact('tag')
        )->title($tag->name);
    }
}
