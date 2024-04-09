<?php

namespace App\Livewire\Shared\Comments;

use App\Models\Comment;
use Illuminate\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Comments extends Component
{
    const PER_PAGE = 10;

    #[Locked]
    public int $postId;

    #[Locked]
    public int $postAuthorId;

    /**
     * This variable is used to gather comment ids.
     * The key is the first id of the comment group.
     *
     * @var array<int, array<int>>
     */
    public array $IdsByFirstId = [];

    // bookmark is the last id of the previous page
    public int $bookmark = 0;

    public bool $showMoreButtonIsActive = true;

    public function mount(): void
    {
        $commentIds = Comment::query()
            ->where('post_id', $this->postId)
            ->orderBy('id', 'desc')
            // Plus one is needed here because we need to determine whether there is a next page.
            // If comment ids is less than PER_PAGE, it means there is no next page.
            ->limit(self::PER_PAGE + 1)
            ->pluck('id');

        if ($commentIds->count() > 0) {
            // Use the first id as the key of the group.
            // Only keep the first 10 ids.
            $this->IdsByFirstId[$commentIds->first()] = array_slice($commentIds->all(), 0, self::PER_PAGE);
            // Use the last id as the bookmark.
            // Bookmark will be the next comment group first id.
            // But if the comment ids is less than PER_PAGE, bookmark is meaningless.
            $this->bookmark = $commentIds->last();
        }

        if ($commentIds->count() <= self::PER_PAGE) {
            $this->showMoreButtonIsActive = false;
        }
    }

    // Show more comments
    public function showMore(): void
    {
        $commentIds = Comment::query()
            ->where('post_id', $this->postId)
            // Use bookmark to get the next comment group.
            ->where('id', '<=', $this->bookmark)
            ->orderBy('id', 'desc')
            ->limit(self::PER_PAGE + 1)
            ->pluck('id');

        if ($commentIds->count() > 0) {
            $this->IdsByFirstId[$commentIds->first()] = array_slice($commentIds->all(), 0, self::PER_PAGE);
            $this->bookmark = $commentIds->last();
        }

        if ($commentIds->count() <= self::PER_PAGE) {
            $this->showMoreButtonIsActive = false;
        }
    }

    public function render(): View
    {
        return view('livewire.shared.comments.comments');
    }
}
