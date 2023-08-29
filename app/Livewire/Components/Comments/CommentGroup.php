<?php

namespace App\Livewire\Components\Comments;

use App\Models\Comment;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

class CommentGroup extends Component
{
    #[Locked]
    public int $postId;

    #[Locked]
    public int $postAuthorId;

    public array $ids = [];

    public int|string $groupId = 'new';

    #[On('add-id-to-group-{groupId}')]
    public function addId(int $id): void
    {
        $this->ids[] = $id;
    }

    #[On('remove-id-from-group-{groupId}')]
    public function removeId(int $id): void
    {
        unset($this->ids[array_search($id, $this->ids)]);
    }

    public function render()
    {
        $comments = collect();

        if (count($this->ids) > 0) {
            $comments = Comment::query()
                ->select(['id', 'body', 'user_id', 'created_at', 'updated_at'])
                ->where('post_id', $this->postId)
                ->whereIn('id', $this->ids)
                ->with('user:id,name,email')
                ->latest('id')
                ->get();
        }

        return view('livewire.components.comments.comment-group', compact('comments'));
    }
}
