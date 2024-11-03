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
    public CommentOrder $commentOrder = CommentOrder::LATEST;

    /**
     * Comment ids list, example:
     * [
     *     [1, 2, 3, 4, 5],
     *     [6, 7, 8, 9, 10],
     *     [11, 12, 13, 14, 15],
     * ]
     *
     * @var array<array<int>>
     */
    public array $commentIdsList = [];

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
        $commentIds = $this->getCommentIds();

        $this->updateSkipCounts();
        $this->updateCommentIdsList($commentIds);
        $this->updateShowMoreButtonStatus($commentIds);
    }

    public function render(): View
    {
        return view('livewire.shared.comments.comment-list');
    }

    private function getCommentIds(): array
    {
        return Comment::query()
            ->when(
                $this->commentOrder === CommentOrder::OLDEST,
                function (Builder $query) {
                    $query->oldest('id');
                }
            )
            ->when(
                $this->commentOrder === CommentOrder::LATEST,
                function (Builder $query) {
                    $query->latest('id');
                }
            )
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
            ->pluck('id')
            ->toArray();
    }

    private function updateSkipCounts(): void
    {
        $this->skipCounts += $this->perPage;
    }

    private function updateCommentIdsList(array $commentIds): void
    {
        if (count($commentIds) > 0) {
            $this->commentIdsList[] = array_slice($commentIds, 0, $this->perPage);
        }
    }

    private function updateShowMoreButtonStatus(array $commentIds): void
    {
        if (count($commentIds) <= $this->perPage) {
            $this->showMoreButtonIsActive = false;
        }
    }
}
