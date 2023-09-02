<?php

namespace App\Livewire\Shared\Posts;

use App\Models\Link;
use App\Models\Tag;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class HomeSidemenu extends Component
{
    public function render()
    {
        $popularTags = Cache::remember('popularTags', now()->addDay(), function () {
            // 取出標籤使用次數前 20 名
            return Tag::withCount('posts')
                ->orderByDesc('posts_count')
                ->limit(20)
                ->get();
        });

        $links = Cache::remember('links', now()->addDay(), function () {
            return Link::all();
        });

        return view('livewire.shared.posts.home-sidemenu', compact('popularTags', 'links'));
    }
}
