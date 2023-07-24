<?php

namespace App\Http\Livewire\Users\Information;

use App\Models\Post;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Posts extends Component
{
    use AuthorizesRequests;

    public int $userId;

    /**
     * @var array<Collection>|Collection
     */
    public $posts;

    public string $year;

    public function postPrivateToggle(int $postId): void
    {
        $post = Post::withTrashed()->find($postId);

        $this->authorize('update', $post);

        $post->is_private = ! $post->is_private;

        $post->withoutTimestamps(fn () => $post->save());

        $this->refreshPostsByYear();

        $this->dispatchBrowserEvent('info-badge', [
            'status' => 'success',
            'message' => $post->is_private ? '文章狀態已切換為私人' : '文章狀態已切換為公開',
        ]);
    }

    public function restore(int $postId): void
    {
        $post = Post::withTrashed()->find($postId);

        $this->authorize('update', $post);

        $post->restore();

        $this->refreshPostsByYear();

        $this->dispatchBrowserEvent('info-badge', ['status' => 'success', 'message' => '文章已恢復']);
    }

    public function deletePost(Post $post): void
    {
        $this->authorize('destroy', $post);

        $post->delete();

        $this->refreshPostsByYear();

        $this->emit('refreshUserPosts');

        $this->dispatchBrowserEvent('info-badge', ['status' => 'success', 'message' => '文章已刪除']);
    }

    public function refreshPostsByYear(): void
    {
        $this->posts = Post::whereUserId($this->userId)
            ->when(auth()->id() === $this->userId, function ($query) {
                return $query->withTrashed();
            }, function ($query) {
                return $query->where('is_private', false);
            })
            ->whereYear('created_at', $this->year)
            ->with('category')
            ->latest()
            ->get();
    }

    public function render()
    {
        return view('livewire.users.information.posts');
    }
}
