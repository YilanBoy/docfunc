<?php

use App\Http\Livewire\Posts\Edit;
use App\Models\Post;
use App\Models\User;

use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

test('visitors cannot access the edit pages of other people\'s post', function () {
    $post = Post::factory()->create();

    get(route('posts.edit', ['post' => $post->id]))
        ->assertRedirect(route('login'));
});

test('users cannot access the edit page of other people\'s post', function () {
    $user = User::factory()->create();

    $post = Post::factory()->create();

    $this->actingAs($user);

    get(route('posts.edit', ['post' => $post->id]))
        ->assertForbidden();
});

test('authors can access the edit page of their post', function () {
    $user = User::factory()->create();

    $post = Post::factory()->create();

    $this->actingAs($user);

    get(route('posts.edit', ['post' => $post->id]))
        ->assertForbidden();
});

test('authors can update their posts', function ($categoryId) {
    $post = Post::factory()->create();

    $this->actingAs($post->user);

    $newTitle = str()->random(4);
    $newBody = str()->random(500);

    livewire(Edit::class, ['post' => $post])
        ->set('title', $newTitle)
        ->set('category_id', $categoryId)
        ->set('body', $newBody)
        ->call('update')
        ->assertHasNoErrors();

    $post->refresh();

    $this->assertEquals($post->title, $newTitle);
    $this->assertEquals($post->category_id, $categoryId);
    $this->assertEquals($post->body, $newBody);
})->with('defaultCategoryIds');
