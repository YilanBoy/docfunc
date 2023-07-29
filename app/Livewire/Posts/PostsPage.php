<?php

namespace App\Livewire\Posts;

use Illuminate\Support\Facades\Route;
use Livewire\Component;

class PostsPage extends Component
{
    public function render()
    {
        $title = (Route::currentRouteName() === 'root')
            ? config('app.name')
            : '所有文章';

        return view('livewire.posts.posts-page')->title($title);
    }
}
