<?php

namespace App\Http\Livewire\Comments;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Models\Post;
use App\Notifications\PostComment;
use Livewire\Component;

class CommentBox extends Component
{
    public int $postId;
    public int $commentCount;
    public string $content = '';

    protected $listeners = ['updateCommentCount'];

    protected function rules()
    {
        return (new CommentRequest())->rules();
    }

    protected function messages()
    {
        return (new CommentRequest())->messages();
    }

    // 儲存留言
    public function store()
    {
        abort_if(!auth()->check(), 403);

        $this->validate();

        $post = Post::query()
            ->where('id', $this->postId)
            ->with('comments')
            ->first();

        $comment = Comment::create([
            'post_id' => $post->id,
            'user_id' => auth()->id(),
            'content' => $this->content,
        ]);

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
        $post = Post::findOrFail($this->postId);

        $this->commentCount = $post->comment_count;
    }

    public function render()
    {
        return view('livewire.comments.comment-box');
    }
}
