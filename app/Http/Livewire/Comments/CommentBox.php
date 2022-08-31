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

    protected function rules(): array
    {
        return (new CommentRequest())->rules();
    }

    protected function messages(): array
    {
        return (new CommentRequest())->messages();
    }

    // save the comment
    public function store()
    {
        abort_if(! auth()->check(), 403);

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

        // update comment count in post table
        $post->updateCommentCount();
        // notify the article author of new comments
        $post->user->postNotify(new PostComment($comment));

        // empty the contents of the comment form
        $this->content = '';
        // dispatch the event of alpine.js, close the comment box modal
        $this->dispatchBrowserEvent('comment-box-open', ['open' => false]);

        $this->updateCommentCount();

        // refresh comment list
        $this->emit('refreshCommentsGroup');
    }

    // update comment count in post show page
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
