<?php

namespace App\Livewire\Shared\Users;

use App\Models\Post;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;

class Posts extends Component
{
    use WithPagination;
    use AuthorizesRequests;

    public int $userId;

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
        return view('livewire.shared.users.posts', ['postsGroupByYear' => $postsGroupByYear]);
    }
}
