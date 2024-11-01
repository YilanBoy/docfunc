<?php

namespace App\Livewire\Shared\Comments;

use App\Models\Comment;
use App\Models\Post;
use App\Notifications\NewComment;
use App\Rules\Captcha;
use App\Traits\MarkdownConverter;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
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
        return [
            'body' => ['required', 'min:5', 'max:2000'],
            'captchaToken' => ['required', new Captcha],
        ];
    }

    protected function messages(): array
    {
        return [
            'body.required' => '請填寫留言內容',
            'body.min' => '留言內容至少 5 個字元',
            'body.max' => '留言內容至多 2000 個字元',
            'captchaToken.required' => '未完成驗證',
        ];
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
    public function store(?int $parentId = null): void
    {
        $this->validate();

        DB::transaction(function () use ($parentId) {
            $comment = Comment::create([
                'post_id' => $this->postId,
                'user_id' => auth()->check() ? auth()->id() : null,
                'body' => $this->body,
                'parent_id' => $parentId,
            ]);

            $post = Post::findOrFail($this->postId);

            // Update comment count in post table.
            $post->increment('comment_counts');

            // Notify the article author of new comments.
            $post->user->notifyNewComment(new NewComment($comment));

            $this->dispatch('append-new-id-to-'.($parentId ?? 'root').'-new-comment-group', id: $comment->id);

            $this->dispatch('append-new-id-to-'.($parentId ?? 'root').'-comment-list', id: $comment->id);
        });

        $this->dispatch('close-create-comment-modal');

        $this->dispatch('update-comment-counts');

        $this->dispatch('info-badge', status: 'success', message: '成功新增留言！');

        // Empty the body of the comment form.
        $this->reset('body', 'previewIsEnabled');
    }

    public function render(): View
    {
        return view('livewire.shared.comments.create-comment-modal');
    }
}
