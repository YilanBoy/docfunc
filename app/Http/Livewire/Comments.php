<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Comments extends Component
{
    public $post;
    public $count = 10;
    public $perPage = 10;
    public $showMoreButtonIsActive = false;

    public function mount()
    {
        if ($this->post->comments()->whereNull('parent_id')->count() > $this->count) {
            $this->showMoreButtonIsActive = true;
        }
    }

    public function showMore()
    {
        $this->count += $this->perPage;

        if ($this->count >= $this->post->comments()->whereNull('parent_id')->count()) {
            $this->showMoreButtonIsActive = false;
        }
    }

    public function render()
    {
        return view('livewire.comments');
    }
}
