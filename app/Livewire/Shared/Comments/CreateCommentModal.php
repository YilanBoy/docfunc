<?php

namespace App\Livewire\Shared\Comments;

use App\Http\Requests\CommentWithRecaptchaRequest;
use App\Livewire\Traits\MarkdownConverter;
use App\Models\Comment;
use App\Models\Post;
use App\Notifications\PostComment;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use League\CommonMark\Exception\CommonMarkException;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Throwable;

/**
 * @property string convertedBody 將 markdown 的 body 轉換成 html 格式，set by convertedBody()
 */
class CreateCommentModal extends Component
{
    use MarkdownConverter;

    const FIRST_GROUP_ID = 0;

    public int $postId;

    public string $body = '';

    public bool $convertToHtml = false;

    public string $recaptcha;

    protected function rules(): array
    {
        return (new CommentWithRecaptchaRequest())->rules();
    }

    protected function messages(): array
    {
        return (new CommentWithRecaptchaRequest())->messages();
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

        DB::beginTransaction();

        try {
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

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Caught exception: '.$e->getMessage());

            $this->dispatch('close-create-comment-modal');

            $this->dispatch('info-badge', status: 'danger', message: 'Oops！新增留言失敗！');

            return;
        }

        // empty the body of the comment form
        $this->reset('body', 'convertToHtml');

        $this->dispatch('add-id-to-group-new', id: $comment->id);

        $this->dispatch('close-create-comment-modal');

        $this->dispatch('update-comment-counts');

        $this->dispatch('info-badge', status: 'success', message: '成功新增留言！');
    }

    public function render()
    {
        return view('livewire.shared.comments.create-comment-modal');
    }
}
