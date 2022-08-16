<?php

namespace App\Http\Livewire\Users\Posts;

use Livewire\Component;

class PostCard extends Component
{
    // category
    public string $categoryLink;

    public string $categoryName;

    public string $categoryIcon;

    // post
    public int $postId;

    public string $link;

    public string $title;

    public string $createdAt;

    public string $createdAtDiffForHumans;

    public int $commentCount;

    public int $authorId;

    public function render()
    {
        return view('livewire.users.posts.post-card');
    }
}
