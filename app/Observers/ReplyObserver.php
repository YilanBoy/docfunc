<?php

namespace App\Observers;

use App\Models\Reply;
use App\Notifications\PostReplied;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class ReplyObserver
{
    public function creating(Reply $reply)
    {
        $reply->content = preg_replace('/(\s*(\\r\\n|\\r|\\n)\s*){3,}/u', PHP_EOL . PHP_EOL, $reply->content);
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
