<?php

namespace App\Livewire\Layouts;

use App\Models\Category;
use App\Services\SettingService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class Header extends Component
{
    protected $listeners = ['logout'];

    /**
     * Destroy an authenticated session.
     */
    public function logout()
    {
        Auth::guard('web')->logout();

        session()->invalidate();

        session()->regenerateToken();

        $this->dispatch('info-badge', status: 'success', message: '已成功登出！');

        $this->redirect(route('login'), navigate: true);
    }

    public function render()
    {
        // 因為分類不常調整，這裡使用快取減少對資料庫的讀取，快取時效性設定 1 天
        $categories = Cache::remember('categories', now()->addDay(), function () {
            return Category::all();
        });

        // 是否顯示註冊按鈕
        $showRegisterButton = SettingService::isRegisterAllowed();

        return view('livewire.layouts.header', compact('categories', 'showRegisterButton'));
    }
}
