<?php

namespace App\Http\Livewire\Comments;

use App\Http\Requests\CommentRequest;
use App\Http\Traits\Livewire\GoogleReCaptcha;
use App\Http\Traits\Livewire\MarkdownConverter;
use App\Models\Comment;
use App\Models\Post;
use App\Notifications\PostComment;
use Livewire\Component;

class CreateModal extends Component
{
    use MarkdownConverter;
    use GoogleReCaptcha;

    public int $postId;

    public string $body = '';

    public bool $convertToHtml = false;

    protected function rules(): array
    {
        $rules = (new CommentRequest())->rules();

        if (app()->isProduction()) {
            $rules = $this->addRecaptchaRules($rules);
        }

        return $rules;
    }

    protected function messages(): array
    {
        $messages = (new CommentRequest())->messages();

        if (app()->isProduction()) {
            $messages = $this->addRecaptchaRules($messages);
        }

        return $messages;
    }

    public function getConvertedBodyProperty(): string
    {
        return $this->convertToHtml($this->body);
    }

    public function store(): void
    {
        $this->validate();

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
