<?php

namespace App\Http\Livewire;

use App\Models\Post;
use Livewire\Component;
use App\Models\Comment;
use App\Notifications\PostComment;

class CommentBox extends Component
{
    public int $postId;
    public string $content = '';
    public bool $commentBoxOpen = false;
    public int $commentId = 0;
    public int $commentCount;

    protected $listeners = ['updateCommentCount'];

    protected array $rules = [
        'commentId' => ['required', 'numeric'],
        'content' => ['required', 'min:5', 'max:400'],
    ];

    protected array $messages = [
        'content.required' => '請填寫留言內容',
        'content.min' => '留言內容至少 5 個字元',
        'content.max' => '留言內容至多 400 個字元',
    ];

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

        $this->validate();

        $post = Post::find($this->postId);

        $comment = Comment::create(
            [
                'post_id' => $post->id,
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
        $post->updateCommentCount();
        $this->updateCommentCount();

        // 通知文章作者有新的評論
        $post->user->postNotify(new PostComment($comment));

        // 清空留言表單的內容
        $this->content = '';

        $this->commentBoxOpen = false;
        $this->dispatchBrowserEvent('enableScroll');

        // 更新留言列表
        $this->emit('refreshCommentsGroup');
    }

    // 更新留言數量
    public function updateCommentCount()
    {
        $this->commentCount = Post::find($this->postId)->comment_count;
    }

    public function render()
    {
        return view('livewire.comment-box');
    }
}
