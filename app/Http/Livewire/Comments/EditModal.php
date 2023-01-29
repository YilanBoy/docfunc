<?php

namespace App\Http\Livewire\Comments;

use App\Http\Requests\CommentRequest;
use App\Http\Traits\Livewire\MarkdownConverter;
use App\Models\Comment as CommentModel;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class EditModal extends Component
{
    use AuthorizesRequests;
    use MarkdownConverter;

    public bool $convertToHtml = false;

    // comment group id is used to refresh comment group component
    public int $groupId;

    public int $commentId;

    public string $body = '';

    public string $recaptcha;

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
        return $this->convertToHtml($this->body);
    }

    public function setEditComment(CommentModel $comment, int $groupId)
    {
        $this->convertToHtml = false;
        $this->groupId = $groupId;
        $this->commentId = $comment->id;
        $this->body = $comment->body;

        $this->emit('editCommentWasSet');
    }

    /**
     * @param  CommentModel  $comment
     * @return void
     *
     * @throws AuthorizationException
     */
    public function update(): void
    {
        $comment = CommentModel::find($this->commentId);

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
