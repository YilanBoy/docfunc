<?php

namespace App\Livewire\Shared\Comments;

use App\Enums\CommentOrder;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Renderless;
use Livewire\Component;

class CommentList extends Component
{
    #[Locked]
    public int $maxLayer = 2;

    #[Locked]
    public int $currentLayer = 1;

    #[Locked]
    public int $perPage = 10;

    #[Locked]
    public int $postId;

    #[Locked]
    public int $postAuthorId;

    #[Locked]
    public ?int $parentId = null;

    /**
     * This value will be either the root-comment-list or [comment id]-comment-list,
     * The comment list name is used as the event name to add new comment ids to $newCommentIds.
     */
    #[Locked]
    public string $commentListName = 'root-comment-list';

    #[Locked]
    public CommentOrder $order = CommentOrder::LATEST;

    /**
     * Comments list array, the format is like:
     * [
     *     [
     *         1 => ["id" => 1, "body" => "hello" ...],
     *         2 => ["id" => 2, "body" => "world" ...],
     *     ],
     *     [
     *         3 => ["id" => 3, "body" => "foo" ...],
     *         4 => ["id" => 4, "body" => "bar" ...],
     *     ],
     * ]
     */
    public array $commentsList = [];

    public int $skipCounts = 0;

    public bool $showMoreButtonIsActive = true;

    /**
     * Recording new comments that created by user.
     *
     * @var array<int>
     */
    public array $newCommentIds = [];

    public function mount(): void
    {
        $this->showMoreComments();
    }

    #[Renderless]
    #[On('append-new-id-to-{commentListName}')]
    public function appendNewIdToNewCommentIds(int $id): void
    {
        $this->newCommentIds[] = $id;
    }

    public function showMoreComments(): void
    {
        $comments = $this->getComments();

        $this->updateSkipCounts();
        $this->updateCommentsList($comments);
        $this->updateShowMoreButtonStatus($comments);
    }

    public function render(): View
    {
        return view('livewire.shared.comments.comment-list');
    }

    private function getComments(): array
    {
        return Comment::query()
            ->select(['id', 'user_id', 'body', 'created_at', 'updated_at'])
            // use a sub query to generate children_count column
            ->withCount('children')
            ->when($this->order === CommentOrder::LATEST, function (Builder $query) {
                $query->latest('id');
            })
            ->when($this->order === CommentOrder::OLDEST, function (Builder $query) {
                $query->oldest('id');
            })
            ->when($this->order === CommentOrder::POPULAR, function (Builder $query) {
                $query->orderByDesc('children_count');
            })
            // Don't show new comments, avoid showing duplicate comments,
            // New comments have already showed in new comment group.
            ->whereNotIn('id', $this->newCommentIds)
            ->where('post_id', $this->postId)
            // When parent id is not null,
            // it means this comment list is children of another comment.
            ->where('parent_id', $this->parentId)
            ->skip($this->skipCounts)
            // Plus one is needed here because we need to determine whether there is a next page.
            ->take($this->perPage + 1)
            ->with('user:id,name,email')
            ->with('children')
            ->get()
            ->keyBy('id')
            ->toArray();
    }

    private function updateSkipCounts(): void
    {
        $this->skipCounts += $this->perPage;
    }

    private function updateCommentsList(array $comments): void
    {
        if (count($comments) > 0) {
            $this->commentsList[] = array_slice($comments, 0, $this->perPage, true);
        }
    }

    private function updateShowMoreButtonStatus(array $comments): void
    {
        if (count($comments) <= $this->perPage) {
            $this->showMoreButtonIsActive = false;
        }
    }
}
