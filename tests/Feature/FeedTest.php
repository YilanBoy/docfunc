<?php

use App\Models\Post;

use function Pest\Laravel\get;

test('feed url could be visited', function () {
    get('/post/feed')
        ->assertSuccessful();
});

test('we can see the latest posts in the feed xml', function () {
    $posts = Post::factory()->count(3)->create();

    get('/post/feed')
        ->assertSuccessful()
        ->assertSee(...$posts->map(fn ($post) => $post->title)->all());
});

test('we can only see latest 10 posts in the feed xml', function () {
    Post::factory()->count(11)->create();

    get('/post/feed')
        ->assertSuccessful()
        ->assertDontSee(Post::oldest()->first()->title);
});
