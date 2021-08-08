<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Reply;
use App\Notifications\PostReplied;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Replies extends Component
{
    use AuthorizesRequests;

    public $post;
    public $reply;
    public $response;

    // protected $rules = [
    //     'reply' => ['required', 'min:2', 'max:400'],
    // ];

    // protected $messages = [
    //     'reply.required' => '請填寫回覆內容',
    //     'reply.min' => '回覆內容至少 2 個字元',
    //     'reply.max' => '回覆內容至多 400 個字元',
    // ];

    // // 實時判斷表單內容是否符合 $rules
    // public function updated($propertyName)
    // {
    //     $this->validateOnly($propertyName);
    // }

    // 儲存回覆
    public function storeReply()
    {
        $this->validate([
            'reply' => ['required', 'min:2', 'max:400'],
        ], [
            'reply.required' => '請填寫回覆內容',
            'reply.min' => '回覆內容至少 2 個字元',
            'reply.max' => '回覆內容至多 400 個字元',
        ]);

        $reply = Reply::create(
            [
                'post_id' => $this->post->id,
                'user_id' => auth()->id(),
                'content' => preg_replace(
                    '/(\s*(\\r\\n|\\r|\\n)\s*){3,}/u',
                    PHP_EOL . PHP_EOL,
                    $this->reply
                ),
            ]
        );

        // 更新文章留言數
        $reply->post->updateReplyCount();

        // 通知文章作者有新的評論
        $reply->post->user->postNotify(new PostReplied($reply));

        // 清空回覆表單的內容
        $this->reply = '';
    }

    public function storeResponse(int $replyId)
    {
        $this->validate([
            'response' => ['required', 'min:2', 'max:400'],
        ], [
            'response.required' => '請填寫回覆內容',
            'response.min' => '回覆內容至少 2 個字元',
            'response.max' => '回覆內容至多 400 個字元',
        ]);

        $response = Reply::create(
            [
                'post_id' => $this->post->id,
                'user_id' => auth()->id(),
                'reply_id' => $replyId,
                'content' => preg_replace(
                    '/(\s*(\\r\\n|\\r|\\n)\s*){3,}/u',
                    PHP_EOL . PHP_EOL,
                    $this->response
                ),
            ]
        );

        // 更新文章留言數
        $response->post->updateReplyCount();

        // 通知文章作者有新的評論
        $response->post->user->postNotify(new PostReplied($response));

        // 清空回覆表單的內容
        $this->response = '';
    }

    // 刪除回覆
    public function destroy(Reply $reply)
    {
        $this->authorize('destroy', $reply);

        $reply->delete();

        $reply->post->updateReplyCount();
    }

    public function render()
    {
        $replies = $this->post->replies()
            // 不顯示子回覆
            ->whereNull('reply_id')
            ->oldest()
            ->get();

        return view('livewire.replies', ['replies' => $replies]);
    }
}
