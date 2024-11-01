<?php

namespace App\Livewire\Shared\Comments;

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

    /**
     * @var array<int>
     */
    public array $commentIds = [];

    /**
     * Comment ids group. The index is the first id of the comment id group.
     *
     * example:
     * [
     *     1 => [1, 2, 3, 4, 5],
     *     6 => [6, 7, 8, 9, 10],
     *     11 => [11, 12, 13, 14, 15],
     * ]
     *
     * @var array<int, array<int>>
     */
    public array $commentIdsGroupByFirstId = [];

    /**
     * Bookmark id is the last id of the previous page,
     * it will be used to get next comment ids.
     */
    public int $bookmarkId = 0;

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
        $this->updateCommentIds();
        $this->updateBookmarkId();
        $this->updateCommentIdsGroup();
        $this->updateShowMoreButtonStatus();
    }

    public function render(): View
    {
        return view('livewire.shared.comments.comment-list');
    }

    private function updateCommentIds(): void
    {
        $this->commentIds = Comment::query()
            // Use bookmark to get the next comment group.
            ->where('id', '>=', $this->bookmarkId)
            ->where('post_id', $this->postId)
            // Don't show new comments, avoid showing duplicate comments,
            // New comments have already showed in new comment group.
            ->whereNotIn('id', $this->newCommentIds)
            // When comments is children of another comment
            ->when(! is_null($this->parentId), function (Builder $query) {
                // When comments is not children
                $query->where('parent_id', $this->parentId);
            }, function (Builder $query) {
                // Only show parent comments
                $query->whereNull('parent_id');
            })
            ->oldest('id')
            // Plus one is needed here because we need to determine whether there is a next page.
            // If comment ids is less than per page, it means there is no next page.
            ->limit($this->perPage + 1)
            ->pluck('id')
            ->toArray();
    }

    private function updateBookmarkId(): void
    {
        if (count($this->commentIds) > 0) {
            // Use the last comment id as the bookmark id.
            // Bookmark id will be the next comment group first id.
            $this->bookmarkId = $this->commentIds[array_key_last($this->commentIds)];
        }
    }

    private function updateCommentIdsGroup(): void
    {
        if (count($this->commentIds) > 0) {
            // Use the first comment id as the key of the group.
            $this->commentIdsGroupByFirstId[$this->commentIds[array_key_first($this->commentIds)]]
               = array_slice($this->commentIds, 0, $this->perPage);
        }
    }

    private function updateShowMoreButtonStatus(): void
    {
        if (count($this->commentIds) <= $this->perPage) {
            $this->showMoreButtonIsActive = false;
        }
    }
}
