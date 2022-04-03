<?php

namespace App\Http\Livewire\Layouts;

use App\Models\Category;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class Header extends Component
{
    public function render()
    {
        // 因為分類不常調整，這裡使用快取減少對資料庫的讀取，快取時效性設定 1 天
        $categories = Cache::remember('categories', now()->addDay(), function () {
            return Category::all();
        });

        return view('livewire.layouts.header', compact('categories'));
    }
}
