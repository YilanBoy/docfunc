<?php

namespace App\Http\Livewire\Comments;

use App\Http\Requests\CommentRequest;
use App\Models\Comment as CommentModel;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Str;

class EditModal extends Component
{
    use AuthorizesRequests;

    public CommentModel $comment;

    public string $body = '';

    public bool $convertToHtml = false;

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
        return Str::of($this->body)->markdown([
            'html_input' => 'strip',
        ]);
    }

    public function setEditComment(int $commentId)
    {
        $this->convertToHtml = false;
        $this->comment = CommentModel::findOrFail($commentId);
        $this->body = $this->comment->body;

        $this->emit('editCommentWasSet');
    }

    public function update()
    {
        $this->authorize('update', $this->comment);

        $this->comment->update([
            'body' => $this->body,
        ]);

        $this->emit('closeEditCommentModal');

        // refresh comment list
        $this->emit('refreshCommentGroup');
    }

    public function render()
    {
        return view('livewire.comments.edit-modal');
    }
}
