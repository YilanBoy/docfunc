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
     * This value will be either the root or comment id,
     * which will be used to represent the entire comments list.
     */
    #[Locked]
    public string $commentListName = 'root';

    /**
     * This variable is used to gather comment ids.
     * The key is the first id of the comment group.
     *
     * @var array<int, array<int>>
     */
    public array $IdsByFirstId = [];

    /**
     * Bookmark is the last id of the previous page,
     * it will be used to get next comments.
     */
    public int $bookmark = 0;

    public bool $showMoreButtonIsActive = true;

    /**
     * Recording new comments that created by user.
     *
     * @var array<int>
     */
    public array $newUserCommentIds = [];

    public function mount(): void
    {
        $commentIds = Comment::query()
            ->where('post_id', $this->postId)
            // When comments is children of another comment
            ->when(is_null($this->parentId), function (Builder $query) {
                // Only show parent comments
                $query->whereNull('parent_id');
            })
            // When comments is not children
            ->when(! is_null($this->parentId), function (Builder $query) {
                $query->where('parent_id', $this->parentId);
            })
            ->oldest('id')
            // Plus one is needed here because we need to determine whether there is a next page.
            // If comment ids is less than PER_PAGE, it means there is no next page.
            ->limit($this->perPage + 1)
            ->pluck('id');

        if ($commentIds->count() > 0) {
            // Use the first id as the key of the group.
            // Only keep the first 10 ids.
            $this->IdsByFirstId[$commentIds->first()] = array_slice($commentIds->all(), 0, $this->perPage);
            // Use the last id as the bookmark.
            // Bookmark will be the next comment group first id.
            // But if the comment ids is less than PER_PAGE, bookmark is meaningless.
            $this->bookmark = $commentIds->last();
        }

        if ($commentIds->count() <= $this->perPage) {
            $this->showMoreButtonIsActive = false;
        }
    }

    #[Renderless]
    #[On('append-new-id-to-{commentListName}')]
    public function appendNewUserCommentId(int $id): void
    {
        $this->newUserCommentIds[] = $id;
    }

    public function showMore(): void
    {
        $commentIds = Comment::query()
            ->where('post_id', $this->postId)
            // Use bookmark to get the next comment group.
            ->where('id', '>=', $this->bookmark)
            // Don't show new comments, avoid showing duplicate comments,
            // New comments have already showed in new comment group.
            ->whereNotIn('id', $this->newUserCommentIds)
            ->when(is_null($this->parentId), function (Builder $query) {
                $query->whereNull('parent_id');
            })
            ->when(! is_null($this->parentId), function (Builder $query) {
                $query->where('parent_id', $this->parentId);
            })
            ->oldest('id')
            ->limit($this->perPage + 1)
            ->pluck('id');

        if ($commentIds->count() > 0) {
            $this->IdsByFirstId[$commentIds->first()] = array_slice($commentIds->all(), 0, $this->perPage);
            $this->bookmark = $commentIds->last();
        }

        if ($commentIds->count() <= $this->perPage) {
            $this->showMoreButtonIsActive = false;
        }
    }

    public function render(): View
    {
        return view('livewire.shared.comments.comment-list');
    }
}
