<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Reply;
use App\Notifications\PostReplied;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ReplyBox extends Component
{
    public $post;
    public $content;
    public $replyId;

    protected $listeners = ['changeReplyId' => 'changeReplyId'];


    protected $rules = [
        'content' => ['required', 'min:2', 'max:400'],
    ];

    protected $messages = [
        'content.required' => '請填寫回覆內容',
        'content.min' => '回覆內容至少 2 個字元',
        'content.max' => '回覆內容至多 400 個字元',
    ];

    // 實時判斷表單內容是否符合 $rules
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function changeReplyId(?int $replyId)
    {
        $this->replyId = $replyId;
    }

    // 儲存回覆
    public function store()
    {
        if (!auth()->check()) {
            return abort(403);
        }

        $this->validate();

        $reply = Reply::create(
            [
                'post_id' => $this->post->id,
                'user_id' => auth()->id(),
                'parent_id' => $this->replyId,
                'content' => preg_replace(
                    '/(\s*(\\r\\n|\\r|\\n)\s*){3,}/u',
                    PHP_EOL . PHP_EOL,
                    $this->content
                ),
            ]
        );

        // 更新文章留言數
        $reply->post->updateReplyCount();

        // 通知文章作者有新的評論
        $reply->post->user->postNotify(new PostReplied($reply));

        // 清空回覆表單的內容
        $this->content = '';

        $this->emit('refreshReplies');
    }

    public function render()
    {
        return view('livewire.reply-box');
    }
}
