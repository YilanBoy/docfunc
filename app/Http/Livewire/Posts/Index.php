<?php

namespace App\Http\Livewire\Posts;

use Illuminate\Support\Facades\Route;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        $title = (Route::currentRouteName() === 'root')
            ? config('app.name')
            : '所有文章';

        return view('livewire.posts.index', compact('title'));
    }
}
