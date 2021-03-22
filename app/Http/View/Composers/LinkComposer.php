<?php

namespace App\Http\View\Composers;

use App\Models\Link;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;

class LinkComposer
{
    public function compose(View $view)
    {
        $links = Cache::remember('links', now()->addDay(), function () {
            return Link::all();
        });
        // 取得所有連結資料並放入變數 links
        $view->with('links', $links);
    }
}
