<?php

namespace App\Http\View\Composers;

use App\Models\Link;
use Illuminate\View\View;
use Cache;

class LinkComposer
{
    // 設定用來依賴注入的變數
    protected $link;

    public function __construct(Link $link)
    {
        // 將 Link Model 依賴注入到 LinkComposer
        $this->link = $link;
    }

    public function compose(View $view)
    {
        // 這裡使用快取減少對資料庫的讀取，快取設定 86400 秒(一天)過期
        $links = Cache::remember('links', 86400, function () {
            return $this->link->all();
        });
        // 取得所有連結資料並放入變數 links
        $view->with('links', $links);
    }
}
