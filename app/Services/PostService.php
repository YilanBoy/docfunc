<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use HTMLPurifier;
use HTMLPurifier_Config;
use JetBrains\PhpStorm\Pure;

class PostService
{
    /**
     * 生成用來優化 SEO 的 slug
     *
     * @param string $title 標題
     * @return string
     */
    public static function makeSlug(string $title): string
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

    /**
     * 過濾 html 格式的文章內容，避免 XSS 攻擊
     *
     * @param string $html
     * @return string
     */
    public static function htmlPurifier(string $html): string
    {
        // 設定 XSS 過濾規則
        $config = HTMLPurifier_Config::createDefault();
        // 設置配置的名稱
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

    /**
     * 生成文章內容的摘錄
     *
     * @param string $body
     * @param int $length
     * @return string
     */
    #[Pure]
    public static function makeExcerpt(string $body, int $length = 200): string
    {
        return Str::limit(strip_tags($body), $length);
    }

    /**
     * 取得文章中的圖片連結
     *
     * @param Post $post
     * @return array
     */
    public static function imagesInPost(Post $post): array
    {
        preg_match_all(
            '/images\/(\d{4}_\d{2}_\d{2}_\d{2}_\d{2}_\d{2}_[a-zA-Z0-9]+\.(jpeg|png|jpg|gif|svg))/U',
            $post->body,
            $matches,
        );

        return $matches[1];
    }
}
