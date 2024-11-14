<?php

namespace App\Livewire\Shared\Comments;

use App\Enums\CommentOrder;
use App\Models\Comment;
use App\Traits\MarkdownConverter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use League\CommonMark\Exception\CommonMarkException;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Renderless;
use Livewire\Component;

class CommentList extends Component
{
    use MarkdownConverter;

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
     *     'user': array{'id': int, 'name': string, 'gravatar_url': string}|null,
     *     'converted_body': string
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

    /**
     * @throws CommonMarkException
     */
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

    /**
     * @throws CommonMarkException
     */
    private function getComments(int $skip): array
    {
        $comments = Comment::query()
            ->select(['id', 'user_id', 'body', 'created_at', 'updated_at'])
            // Use a sub query to generate children_count column,
            // this line must be after select method
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
            ->skip($skip)
            // Plus one is needed here because we need to determine whether there is a next page.
            ->take($this->perPage + 1)
            ->with('user:id,name,email')
            ->get()
            ->keyBy('id')
            ->toArray();

        return array_map(function ($comment): array {
            $comment['converted_body'] = $this->convertToHtml($comment['body']);

            if (! is_null($comment['user'])) {
                $comment['user']['gravatar_url'] = get_gravatar($comment['user']['email']);
                unset($comment['user']['email']);
            }

            return $comment;
        }, $comments);
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

    /**
     * @throws CommonMarkException
     */
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
