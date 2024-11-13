<?php

namespace App\Livewire\Shared\Comments;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
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

        // If parent post has already been deleted.
        $post = Post::findOr(id: $this->postId, columns: ['id', 'comment_counts', 'user_id'], callback: function () {
            $this->dispatch(event: 'info-badge', status: 'danger', message: '無法回覆！文章已被刪除！');

            $this->redirect(url: route('posts.index'), navigate: true);
        });

        if (is_null($post)) {
            return;
        }

        // If parent comment has already been deleted.
        if (! is_null($parentId)) {
            $parentComment = Comment::findOr(id: $parentId, columns: ['id'], callback: function () {
                $this->dispatch(event: 'info-badge', status: 'danger', message: '無法回覆！留言已被刪除！');
            });

            if (is_null($parentComment)) {
                return;
            }
        }

        $comment = null;

        DB::transaction(function () use (&$comment, $post, $parentId) {
            $comment = Comment::create([
                'post_id' => $this->postId,
                // auth()->id() will be null if user is not logged in
                'user_id' => auth()->id(),
                'body' => $this->body,
                'parent_id' => $parentId,
            ]);

            $post->increment('comment_counts');
        });

        // Notify the article author of new comments.
        $post->user->notifyNewComment(new NewComment($comment));

        $this->dispatch(
            event: 'create-new-comment-to-'.($parentId ?? 'root').'-new-comment-group',
            comment: $this->prepareCommentCardArray($comment, auth()->user())
        );

        $this->dispatch(event: 'append-new-id-to-'.($parentId ?? 'root').'-comment-list', id: $comment->id);

        $this->dispatch(event: 'close-create-comment-modal');

        $this->dispatch(event: 'update-comment-counts');

        $this->dispatch(event: 'info-badge', status: 'success', message: '成功新增留言！');

        $this->reset('body', 'previewIsEnabled');
    }

    public function render(): View
    {
        return view('livewire.shared.comments.create-comment-modal');
    }

    /**
     * @throws CommonMarkException
     */
    private function prepareCommentCardArray(Comment $comment, ?User $user): array
    {
        $comment = $comment->toArray();

        $comment['converted_body'] = $this->convertToHtml($comment['body']);
        $comment['children_count'] = 0;

        if (is_null($user)) {
            $comment['user'] = null;

            return $comment;
        }

        $comment['user'] = [
            'id' => $user->id,
            'name' => $user->name,
            'gravatar_url' => get_gravatar($user->email),
        ];

        return $comment;
    }
}
