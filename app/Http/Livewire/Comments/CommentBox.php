<?php

namespace App\Http\Livewire\Comments;

use App\Models\Post;
use Livewire\Component;
use App\Models\Comment;
use App\Notifications\PostComment;

class CommentBox extends Component
{
    public int $postId;
    public int $commentCount;
    public string $content = '';

    protected $listeners = ['updateCommentCount'];

    protected array $rules = [
        'content' => ['required', 'min:5', 'max:400'],
    ];

    protected array $messages = [
        'content.required' => '請填寫留言內容',
        'content.min' => '留言內容至少 5 個字元',
        'content.max' => '留言內容至多 400 個字元',
    ];

    // 儲存留言
    public function store()
    {
        $this->validate();

        $post = Post::where('id', $this->postId)
            ->with('comments')
            ->first();

        // 判斷父留言是否為當前文章的留言
        abort_if(!auth()->check(), 403);

        $comment = Comment::create(
            [
                'post_id' => $post->id,
                'user_id' => auth()->id(),
                'content' => $this->content,
            ]
        );

        // 更新資料庫的文章留言數
        $post->updateCommentCount();
        // 通知文章作者有新的評論
        $post->user->postNotify(new PostComment($comment));

        // 清空留言表單的內容
        $this->content = '';
        // 觸發 alpine.js 的事件，關閉 comment box modal
        $this->dispatchBrowserEvent('set-comment-box-open', ['open' => false]);
        // 更新頁面上的留言數量
        $this->updateCommentCount();

        // 更新留言列表
        $this->emit('refreshCommentsGroup');
    }

    // 更新頁面上的留言數量
    public function updateCommentCount()
    {
        $this->commentCount = Post::findOrFail($this->postId)->comment_count;
    }

    public function render()
    {
        return view('livewire.comments.comment-box');
    }
}
