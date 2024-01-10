<?php


use App\Models\Comment;
use App\Models\Post;

it('has comments', function () {
    $post = Post::factory()
        ->has(Comment::factory()->count(3))
        ->create();

    expect($post->comments)
        ->toHaveCount(3)
        ->each->toBeInstanceOf(Comment::class);
});
