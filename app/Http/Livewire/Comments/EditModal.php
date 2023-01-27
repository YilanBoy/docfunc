<?php

namespace App\Http\Livewire\Comments;

use App\Http\Requests\CommentRequest;
use App\Http\Traits\Livewire\MarkdownConverter;
use App\Models\Comment as CommentModel;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class EditModal extends Component
{
    use AuthorizesRequests;
    use MarkdownConverter;

    public bool $convertToHtml = false;

    // comment group id is used to refresh comment group component
    public int $groupId;

    public ?int $commentId = null;

    public string $body = '';

    protected $listeners = ['setEditComment'];

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
        return $this->convertToMarkdown($this->body);
    }

    public function setEditComment(CommentModel $comment, int $groupId)
    {
        $this->convertToHtml = false;
        $this->groupId = $groupId;
        $this->commentId = $comment->id;
        $this->body = $comment->body;

        $this->emit('editCommentWasSet');
    }

    public function update(CommentModel $comment)
    {
        $this->authorize('update', $comment);

        $comment->update([
            'body' => $this->body,
        ]);

        $this->emit('closeEditCommentModal');

        // refresh comment list
        $this->emit('refreshCommentGroup'.$this->groupId);
    }

    public function render()
    {
        return view('livewire.comments.edit-modal');
    }
}
