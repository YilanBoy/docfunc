<?php

namespace App\Http\Livewire\Comments;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Models\Post;
use App\Notifications\PostComment;
use Livewire\Component;
use Str;

class CreateModal extends Component
{
    public int $postId;

    public string $body = '';

    public bool $convertToHtml = false;

    protected function rules(): array
    {
        return (new CommentRequest())->rules();
    }

    protected function messages(): array
    {
        return (new CommentRequest())->messages();
    }

    public function getConvertedBodyProperty(): string
    {
        return Str::of($this->body)->markdown([
            'html_input' => 'strip',
        ]);
    }

    public function store()
    {
        abort_if(! auth()->check(), 403);

        $this->validate();

        $comment = Comment::create([
            'post_id' => $this->postId,
            'user_id' => auth()->id(),
            'body' => $this->body,
        ]);

        $post = Post::findOrFail($this->postId);

        // update comment count in post table
        $post->increment('comment_counts');
        // notify the article author of new comments
        $post->user->postNotify(new PostComment($comment));

        // empty the body of the comment form
        $this->reset('body');

        $this->emit('closeCreateCommentModal');

        $this->emit('updateCommentCounts');

        // refresh comment list
        $this->emit('refreshAllCommentGroup');
    }

    public function render()
    {
        return view('livewire.comments.create-modal');
    }
}
