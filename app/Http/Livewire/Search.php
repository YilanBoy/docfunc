<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Post;

class Search extends Component
{
    public $search;

    public function render()
    {
        $results = collect();

        if (strlen($this->search) >= 2) {
            $results = Post::search($this->search)->take(10)->get();
        }

        return view('livewire.search', ['results' => $results]);
    }
}
