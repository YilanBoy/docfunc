<?php

namespace App\Http\Livewire\Users\Information;

use App\Models\Post;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;

class PostsGroupByYear extends Component
{
    use WithPagination;
    use AuthorizesRequests;

    public int $userId;

    public function updatedPaginators(): void
    {
        $this->dispatchBrowserEvent('scroll-to-top');
    }

    public function render()
    {

        $posts = Post::whereUserId($this->userId)
            ->when(auth()->id() === $this->userId, function ($query) {
                return $query->withTrashed();
            }, function ($query) {
                return $query->where('is_private', false);
            })
            ->with('category')
            ->latest()
            ->get();

        $postsGroupByYear = [];

        foreach ($posts as $post) {
            $year = $post->created_at->format('Y');

            if (! isset($postsGroupByYear[$year])) {
                $postsGroupByYear[$year] = [];
            }

            $postsGroupByYear[$year][] = $post;
        }

        // 該會員的文章
        return view('livewire.users.information.posts-group-by-year', ['postsGroupByYear' => $postsGroupByYear]);
    }
}
