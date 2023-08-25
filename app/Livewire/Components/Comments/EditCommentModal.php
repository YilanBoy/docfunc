<?php

namespace App\Livewire\Components\Comments;

use App\Http\Requests\CommentRequest;
use App\Http\Traits\Livewire\MarkdownConverter;
use App\Models\Comment as CommentModel;
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
    public function setEditComment(CommentModel $comment): void
    {
        $this->convertToHtml = false;
        $this->commentId = $comment->id;
        $this->body = $comment->body;

        $this->dispatch('edit-comment-was-set');
    }

    /**
     * @throws AuthorizationException
     */
    public function update(): void
    {
        $comment = CommentModel::find($this->commentId);

        $this->authorize('update', $comment);

        $comment->update([
            'body' => $this->body,
        ]);

        $this->dispatch('close-edit-comment-modal');

        $this->dispatch('comment-updated.'.$comment->id);
    }

    public function render()
    {
        return view('livewire.components.comments.edit-comment-modal');
    }
}
