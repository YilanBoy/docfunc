<?php

namespace App\Observers;

use App\Models\Post;
use HTMLPurifier;
use HTMLPurifier_Config;
use App\Services\PostService;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class PostObserver
{
    protected $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function saving(Post $post)
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

        if ($def = $config->maybeGetRawHTMLDefinition()) {
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
        // 過濾文章內文
        $post->body = $purifier->purify($post->body);
        // 生成摘錄
        $post->excerpt = $this->postService->make_excerpt($post->body);
    }
}
