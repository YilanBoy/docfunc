<?php

namespace App\Http\Livewire\Layouts\Header;

use App\Models\Category;
use App\Services\SettingService;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class MobileMenu extends Component
{
    public function render()
    {
        // 因為分類不常調整，這裡使用快取減少對資料庫的讀取，快取時效性設定 1 天
        $categories = Cache::remember('categories', now()->addDay(), function () {
            return Category::all();
        });

        // 是否顯示註冊按鈕
        $showRegisterButton = SettingService::isRegisterAllowed();

        return view('livewire.layouts.header.mobile-menu', compact('categories', 'showRegisterButton'));
    }
}
