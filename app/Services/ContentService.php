<?php

namespace App\Services;

use DOMDocument;
use HTMLPurifier;
use HTMLPurifier_Config;

class ContentService
{
    /**
     * 生成用來優化 SEO 的 slug
     *
     * @param  string  $title 標題
     * @return string
     */
    public function makeSlug(string $title): string
    {
        // 去掉特殊字元，只留中文與英文
        $title = preg_replace('/[^A-Za-z0-9 \p{Han}]+/u', '', $title);
        // 將空白替換成 '-'
        $title = preg_replace('/\s+/u', '-', $title);
        // 英文全部改為小寫
        $title = strtolower($title);

        // 後面加個 '-post' 是為了避免 slug 只有 'edit' 時，會與編輯頁面的路由發生衝突
        return $title.'-post';
    }

    /**
     * 過濾 html 格式的文章內容，避免 XSS 攻擊
     *
     * @param  string  $html
     * @return string
     */
    public function htmlPurifier(string $html): string
    {
        // 設定 XSS 過濾規則
        $config = HTMLPurifier_Config::createDefault();
        // 設置配置的名稱
        $config->set('HTML.DefinitionID', 'wysiwyg editon');
        // 設置配置的版本
        $config->set('HTML.DefinitionRev', 1);
        // 清除過濾規則的快取，正式上線時必須移除
        if (app()->isLocal()) {
            $config->set('Cache.DefinitionImpl', null);
        }
        // 允許連結使用 target="_blank"
        $config->set('Attr.AllowedFrameTargets', ['_blank']);
        // 允許連結使用 rel 標籤
        $config->set('Attr.AllowedRel', ['nofollow', 'noreferrer', 'noopener']);

        $def = $config->maybeGetRawHTMLDefinition();

        if ($def) {
            // 圖片
            $def->addElement(
                'figure',
                'Block',
                'Optional: (figcaption, Flow) | (Flow, figcaption) | Flow',
                'Common'
            );
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
                ['url' => 'URI'] // 屬性
            );
        }

        // 設定 XSS 過濾器
        $purifier = new HTMLPurifier($config);

        return $purifier->purify($html);
    }

    /**
     * 生成文章內容的摘錄
     *
     * @param  string  $body
     * @param  int  $length
     * @return string
     */
    public function makeExcerpt(string $body, int $length = 200): string
    {
        return str()->limit(strip_tags($body), $length);
    }

    /**
     * 取得文章中的圖片連結
     *
     * @param  string  $body
     * @return array
     */
    public function imagesInContent(string $body): array
    {
        $dom = new DOMDocument();
        $dom->loadHTML($body, LIBXML_NOERROR);

        $imageList = [];

        foreach ($dom->getElementsByTagName('img') as $img) {
            $pattern = '/\d{4}_\d{2}_\d{2}_\d{2}_\d{2}_\d{2}_[a-zA-Z0-9]+\.(jpeg|png|jpg|gif|svg)/u';

            $imageName = basename($img->getAttribute('src'));

            if (preg_match($pattern, $imageName)) {
                $imageList[] = $imageName;
            }
        }

        // format:
        // [
        //     '2023_01_01_10_18_21_63b0ed6d06d52.jpg',
        //     '2022_12_30_22_39_21_63aef81999216.jpg',
        //     ...
        // ]
        return $imageList;
    }
}
