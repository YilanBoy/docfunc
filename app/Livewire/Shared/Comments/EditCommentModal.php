<?php

namespace App\Livewire\Shared\Comments;

use App\Http\Requests\CommentRequest;
use App\Livewire\Traits\MarkdownConverter;
use App\Models\Comment;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\On;
use Livewire\Component;

class EditCommentModal extends Component
{
    use AuthorizesRequests;
    use MarkdownConverter;

    public bool $convertToHtml = false;

    public int $commentId;

    public string $body = '';

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

    #[On('set-edit-comment')]
    public function setEditComment(Comment $comment): void
    {
        $this->convertToHtml = false;
        $this->commentId = $comment->id;
        $this->body = $comment->body;

        $this->dispatch('edit-comment-was-set');
    }

    /**
     * @throws AuthorizationException
     */
    public function update(Comment $comment): void
    {
        $this->authorize('update', $comment);

        $comment->update([
            'body' => $this->body,
        ]);

        $this->dispatch('close-edit-comment-modal');

        $this->dispatch('comment-updated.'.$comment->id);
    }

    public function render()
    {
        return view('livewire.shared.comments.edit-comment-modal');
    }
}
