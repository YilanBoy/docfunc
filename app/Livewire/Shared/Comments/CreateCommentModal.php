<?php

namespace App\Livewire\Shared\Comments;

use App\DataTransferObjects\CommentCardData;
use App\Models\Comment;
use App\Models\Post;
use App\Notifications\NewComment;
use App\Rules\Captcha;
use App\Traits\MarkdownConverter;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Throwable;

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
     * @throws Throwable
     */
    public function store(?int $parentId = null): void
    {
        $this->validate();

        // If post has already been deleted.
        $post = Post::find(id: $this->postId, columns: ['id', 'user_id']);

        if (is_null($post)) {
            $this->dispatch(event: 'info-badge', status: 'danger', message: '無法回覆！文章已被刪除！');

            $this->redirect(url: route('posts.index'), navigate: true);

            return;
        }

        // If parent comment has already been deleted.
        if (! is_null($parentId)) {
            $parentComment = Comment::find(id: $parentId, columns: ['id']);

            if (is_null($parentComment)) {
                $this->dispatch(event: 'info-badge', status: 'danger', message: '無法回覆！留言已被刪除！');

                return;
            }
        }

        $comment = Comment::create([
            'post_id' => $this->postId,
            // auth()->id() will be null if user is not logged in
            'user_id' => auth()->id(),
            'body' => $this->body,
            'parent_id' => $parentId,
        ]);

        // Notify the article author of new comments.
        $post->user->notifyNewComment(new NewComment($comment));

        $commentCard = new CommentCardData(
            id: $comment->id,
            userId: auth()->id(),
            body: $comment->body,
            createdAt: $comment->created_at->toDateTimeString(),
            updatedAt: $comment->updated_at->toDateTimeString(),
            user: auth()->check() ? [
                'id' => auth()->id(),
                'name' => auth()->user()->name,
                'gravatar_url' => get_gravatar(auth()->user()->email),
            ] : null,
        );

        $this->dispatch(
            event: 'create-new-comment-to-'.($parentId ?? 'root').'-new-comment-group',
            comment: $commentCard->toArray(),
        );

        $this->dispatch(event: 'append-new-id-to-'.($parentId ?? 'root').'-comment-list', id: $comment->id);

        $this->dispatch(event: 'close-create-comment-modal');

        $this->dispatch(event: 'update-comments-count');

        $this->dispatch(event: 'info-badge', status: 'success', message: '成功新增留言！');

        $this->reset('body', 'previewIsEnabled');
    }

    public function render(): View
    {
        return view('livewire.shared.comments.create-comment-modal');
    }
}
