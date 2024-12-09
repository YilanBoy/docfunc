<?php

namespace App\Livewire\Forms;

use App\Models\Comment;
use Livewire\Attributes\Validate;
use Livewire\Form;

class CommentForm extends Form
{
    #[Validate('required')]
    #[Validate('numeric')]
    public int $post_id;

    #[Validate('nullable')]
    #[Validate('numeric')]
    public ?int $user_id = null;

    #[Validate('nullable')]
    #[Validate('numeric')]
    public ?int $parent_id = null;

    #[Validate('required', message: '請填寫留言內容')]
    #[Validate('min:5', message: '留言內容至少 5 個字元')]
    #[Validate('max:2000', message: '留言內容最多 2000 個字元')]
    public string $body = '';

    public function store(): Comment
    {
        $this->validate();

        return Comment::create($this->all());
    }

    public function update(Comment $comment): void
    {
        $this->post_id = $comment->post_id;
        $this->user_id = $comment->user_id;
        $this->parent_id = $comment->parent_id;

        $this->validate();

        $comment->update($this->all());
    }
}
