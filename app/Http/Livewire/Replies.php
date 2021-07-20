<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Reply;
use App\Notifications\PostReplied;
use Livewire\WithPagination;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Replies extends Component
{
    use AuthorizesRequests, WithPagination;

    public $post;
    public $content;

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

    // 儲存回覆
    public function store()
    {
        $this->validate();

        $reply = Reply::create(
            [
                'post_id' => $this->post->id,
                'user_id' => auth()->id(),
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

        $this->resetPage();
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
            ->with('user', 'post')
            ->latest()
            ->paginate(10);

        return view('livewire.replies', ['replies' => $replies]);
    }
}
