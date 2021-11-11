<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Comment;
use App\Notifications\PostComment;

class CommentBox extends Component
{
    public $post;
    public $content;
    public $commentId = 0;
    public $commentCount;

    protected $listeners = ['updateCommentCount'];

    protected $rules = [
        'content' => ['required', 'min:2', 'max:400'],
    ];

    protected $messages = [
        'content.required' => '請填寫留言內容',
        'content.min' => '留言內容至少 2 個字元',
        'content.max' => '留言內容至多 400 個字元',
    ];

    // 實時判斷表單內容是否符合 $rules
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    // 確認是否有登入
    public function authCheck()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
    }

    // 儲存留言
    public function store()
    {
        if (!auth()->check()) {
            return abort(403);
        }

        $this->validate([
            'commentId' => ['required', 'numeric']
        ]);

        $comment = Comment::create(
            [
                'post_id' => $this->post->id,
                'user_id' => auth()->id(),
                'parent_id' => $this->commentId === 0 ? null : $this->commentId,
                'content' => preg_replace(
                    '/(\s*(\\r\\n|\\r|\\n)\s*){3,}/u',
                    PHP_EOL . PHP_EOL,
                    $this->content
                ),
            ]
        );

        // 更新文章留言數
        $this->post->updateCommentCount();
        $this->updateCommentCount();

        // 通知文章作者有新的評論
        $this->post->user->postNotify(new PostComment($comment));

        // 清空留言表單的內容
        $this->content = '';

        // 更新留言列表
        $this->emit('refreshCommentsGroup');
    }

    // 更新留言數量
    public function updateCommentCount()
    {
        $this->commentCount = $this->post->comment_count;
    }

    public function render()
    {
        return view('livewire.comment-box');
    }
}
