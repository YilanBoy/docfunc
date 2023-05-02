<?php

namespace App\Http\Livewire\Comments;

use App\Http\Requests\CommentRequest;
use App\Http\Traits\Livewire\MarkdownConverter;
use App\Models\Comment;
use App\Models\Post;
use App\Notifications\PostComment;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Throwable;

class CreateModal extends Component
{
    use MarkdownConverter;

    public int $postId;

    public string $body = '';

    public bool $convertToHtml = false;

    public string $recaptcha;

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
        return $this->convertToHtml($this->body);
    }

    /**
     * @throws Throwable
     */
    public function store(): void
    {
        $this->validate();

        DB::transaction(function () {
            $comment = Comment::create([
                'post_id' => $this->postId,
                'user_id' => auth()->check() ? auth()->id() : null,
                'body' => $this->body,
            ]);

            $post = Post::findOrFail($this->postId);

            // update comment count in post table
            $post->increment('comment_counts');
            // notify the article author of new comments
            $post->user->postNotify(new PostComment($comment));
        });

        // empty the body of the comment form
        $this->reset('body');

        $this->dispatchBrowserEvent('close-create-comment-modal');

        $this->emit('updateCommentCounts');

        // refresh comment list
        $this->emit('refreshAllComments');
    }

    public function render()
    {
        return view('livewire.comments.create-modal');
    }
}
