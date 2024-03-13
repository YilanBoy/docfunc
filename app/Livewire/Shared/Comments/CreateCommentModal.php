<?php

namespace App\Livewire\Shared\Comments;

use App\Http\Requests\CommentRequest;
use App\Livewire\Traits\MarkdownConverter;
use App\Models\Comment;
use App\Models\Post;
use App\Notifications\NewComment;
use App\Rules\Captcha;
use Illuminate\Support\Facades\DB;
use League\CommonMark\Exception\CommonMarkException;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Throwable;

/**
 * @property string $convertedBody 將 markdown 的 body 轉換成 html 格式，set by convertedBody()
 */
class CreateCommentModal extends Component
{
    use MarkdownConverter;

    #[Locked]
    public int $postId;

    public string $body = '';

    public bool $previewIsEnabled = false;

    public string $captchaToken = '';

    protected function rules(): array
    {
        $rules = (new CommentRequest())->rules();
        $rules['captchaToken'] = ['required', new Captcha()];

        return $rules;
    }

    protected function messages(): array
    {
        $messages = (new CommentRequest())->messages();
        $messages['captchaToken.required'] = '未完成驗證';

        return $messages;
    }

    /**
     * @throws CommonMarkException
     */
    #[Computed]
    public function convertedBody(): string
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
            $post->user->notifyNewComment(new NewComment($comment));

            $this->dispatch('create-comment-in-group-new', id: $comment->id);
        });

        // empty the body of the comment form
        $this->reset('body', 'previewIsEnabled');

        $this->dispatch('close-create-comment-modal');

        $this->dispatch('update-comment-counts');

        $this->dispatch('info-badge', status: 'success', message: '成功新增留言！');
    }

    public function render()
    {
        return view('livewire.shared.comments.create-comment-modal');
    }
}
