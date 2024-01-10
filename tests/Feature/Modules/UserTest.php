<?php

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;

it('has posts', function () {
    $user = User::factory()
        ->has(Post::factory()->count(3))
        ->create();

    expect($user->posts)
        ->toHaveCount(3)
        ->each->toBeInstanceOf(Post::class);
});

// it has comments
it('has comments', function () {
    $user = User::factory()
        ->has(Comment::factory()->count(3))
        ->create();

    expect($user->comments)
        ->toHaveCount(3)
        ->each->toBeInstanceOf(Comment::class);
});
