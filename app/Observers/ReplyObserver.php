<?php

namespace App\Observers;

use App\Models\Reply;
use App\Notifications\PostReplied;
use HTMLPurifier;
use HTMLPurifier_Config;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class ReplyObserver
{
    public function creating(Reply $reply)
    {
        // 設定 XSS 過濾規則
        $config = HTMLPurifier_Config::createDefault();
        // 設定 XSS 過濾器
        $purifier = new HTMLPurifier($config);
        // 過濾回覆內文
        $reply->content = $purifier->purify($reply->content);
    }

    public function created(Reply $reply)
    {
        $reply->post->updateReplyCount();

        // 通知文章作者有新的評論
        $reply->post->user->postNotify(new PostReplied($reply));
    }

    public function deleted(Reply $reply)
    {
        $reply->post->updateReplyCount();
    }
}
