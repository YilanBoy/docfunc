<?php

namespace App\Livewire\Shared\Comments;

use App\Models\Comment;
use App\Traits\MarkdownConverter;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;

/**
 * @property string $convertedBody 將 markdown 的 body 轉換成 html 格式，set by convertedBody()
 */
class EditCommentModal extends Component
{
    use AuthorizesRequests;
    use MarkdownConverter;

    public bool $previewIsEnabled = false;

    public string $body = '';

    protected function rules(): array
    {
        return [
            'body' => ['required', 'min:5', 'max:2000'],
        ];
    }

    protected function messages(): array
    {
        return [
            'body.required' => '請填寫留言內容',
            'body.min' => '留言內容至少 5 個字元',
            'body.max' => '留言內容至多 2000 個字元',
        ];
    }

    #[Computed]
    public function convertedBody(): string
    {
        return $this->convertToHtml($this->body);
    }

    /**
     * @throws AuthorizationException
     */
    public function update(Comment $comment, string $groupName): void
    {
        $this->authorize('update', $comment);

        $this->validate();

        $comment->update([
            'body' => $this->body,
        ]);

        $this->dispatch(event: 'close-edit-comment-modal');

        $this->dispatch(
            event: 'update-comment-in-'.$groupName,
            id: $comment->id,
            body: $comment->body,
            convertedBody: $this->convertToHtml($comment->body),
            updatedAt: $comment->updated_at,
        );
    }

    public function render(): View
    {
        return view('livewire.shared.comments.edit-comment-modal');
    }
}
