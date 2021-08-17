<?php

namespace App\Services;

use Illuminate\Support\Str;
use HTMLPurifier;
use HTMLPurifier_Config;

class PostService
{
    // 生成 Slug
    public function makeSlug(string $title)
    {
        // 去掉特殊字元，只留中文與英文
        $title = preg_replace('/[^A-Za-z0-9 \p{Han}]+/u', '', $title);

        // 將空白替換成 '-'
        $title = str_replace(' ', '-', $title);

        // 英文全部改為小寫
        $title = strtolower($title);

        // 後面加個 '-post' 是為了避免 slug 只有 'edit' 時，會與編輯頁面的路由發生衝突
        return $title . '-post';
    }

    public function htmlPurifier(string $html)
    {
        // 設定 XSS 過濾規則
        $config = HTMLPurifier_Config::createDefault();
        // 設置配置的名稱
        $config->set('HTML.DefinitionID', 'wysiwyg editon');
        // 設置配置的版本
        $config->set('HTML.DefinitionRev', 1);
        // 過濾規則讀取是使用快取，下面這一行是用來清除快取，正式上線時必須移除
        // $config->set('Cache.DefinitionImpl', null);
        // 允許連結使用 taget="_blank"
        $config->set('Attr.AllowedFrameTargets', ['_blank']);
        // 允許連結使用 rel 標籤
        $config->set('Attr.AllowedRel', ['nofollow', 'noreferrer', 'noopener']);

        $def = $config->maybeGetRawHTMLDefinition();

        if ($def) {
            // 圖片
            $def->addElement('figure', 'Block', 'Optional: (figcaption, Flow) | (Flow, figcaption) | Flow', 'Common');
            // 圖片底下的說明文字
            $def->addElement('figcaption', 'Inline', 'Flow', 'Common');

            // 螢光筆
            $def->addElement('mark', 'Inline', 'Inline', 'Common');

            // 影片嵌入
            $def->addElement(
                'oembed', // 標籤名稱
                'Block', // content set
                'Flow', // allowed children
                'Common', // attribute collection
                [ // 屬性
                    'url' => 'URI',
                ]
            );
        }

        // 設定 XSS 過濾器
        $purifier = new HTMLPurifier($config);

        return $purifier->purify($html);
    }

    // 生成摘錄的方法
    public function makeExcerpt(string $body, int $length = 150)
    {
        return Str::limit(strip_tags($body), $length);
    }
}
