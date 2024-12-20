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
    public int $postId;

    #[Locked]
    public int $postUserId;

    #[Locked]
    public int $maxLayer = 2;

    #[Locked]
    public int $currentLayer = 1;

    #[Locked]
    public ?int $parentId = null;

    #[Locked]
    public int $perPage = 10;

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
     *
     * @var array<int, array<int, array{
     *     'id': int,
     *     'user_id': int|null,
     *     'body': string,
     *     'created_at': string,
     *     'updated_at': string,
     *     'children_count': int,
     *     'user_name': string|null,
     *     'user_gravatar_url': string|null,
     * }>>
     * >
     */
    public array $commentsList = [];

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

    private function getComments(int $skip): array
    {
        $comments = Comment::query()
            ->select([
                'comments.id',
                'comments.user_id',
                'comments.body',
                'comments.created_at',
                'comments.updated_at',
                'users.name as user_name',
                'users.email as user_email'
            ])
            // Use a sub query to generate children_count column,
            // this line must be after select method
            ->withCount('children')
            ->join('users', 'comments.user_id', '=', 'users.id', 'left')
            ->when($this->order === CommentOrder::LATEST, function (Builder $query) {
                $query->latest('comments.id');
            })
            ->when($this->order === CommentOrder::OLDEST, function (Builder $query) {
                $query->oldest('comments.id');
            })
            ->when($this->order === CommentOrder::POPULAR, function (Builder $query) {
                $query->orderByDesc('children_count');
            })
            // Don't show new comments, avoid showing duplicate comments,
            // New comments have already showed in new comment group.
            ->whereNotIn('comments.id', $this->newCommentIds)
            ->where('comments.post_id', $this->postId)
            // When parent id is not null,
            // it means this comment list is children of another comment.
            ->where('comments.parent_id', $this->parentId)
            ->skip($skip)
            // Plus one is needed here because we need to determine whether there is a next page.
            ->take($this->perPage + 1)
            ->get()
            ->keyBy('id')
            ->toArray();


        // Livewire will save data in frontend, so we need to remove sensitive data
        $callback = function (array $comment): array {
            $comment['user_gravatar_url'] = is_null($comment['user_email']) ? null : get_gravatar($comment['user_email']);
            unset($comment['user_email']);

            return $comment;
        };

        return array_map($callback, $comments);
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

    public function showMoreComments(int $skip = 0): void
    {
        $comments = $this->getComments($skip);

        $this->updateCommentsList($comments);
        $this->updateShowMoreButtonStatus($comments);
    }

    public function render(): View
    {
        return view('livewire.shared.comments.comment-list');
    }
}
