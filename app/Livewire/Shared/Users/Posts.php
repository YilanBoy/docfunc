<?php

namespace App\Livewire\Shared\Users;

use App\Models\Post;
use Illuminate\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;

/**
 * @property-read array<string, array<Post>> $postsGroupByYear
 */
class Posts extends Component
{
    public int $userId;

    /**
     * Get the post list of the user. this list will be grouped by year.
     * The first year will be the latest year
     * format: ['2021' => [Post, Post, ...], '2020' => [Post, Post, ...], ...]
     *
     * @return array<string, array<Post>> $postsGroupByYear
     */
    #[Computed]
    public function postsGroupByYear(): array
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

        return $postsGroupByYear;
    }

    public function render(): View
    {
        return view('livewire.shared.users.posts');
    }
}
