<?php

use App\Http\Livewire\Posts\EditForm;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

uses(RefreshDatabase::class);

test('visitors cannot access the edit pages of other people\'s post', function () {
    $post = Post::factory()->create();

    get(route('posts.edit', ['id' => $post->id]))
        ->assertRedirect(route('login'));
});

test('users cannot access the edit page of other people\'s post', function () {
    $user = User::factory()->create();

    $post = Post::factory()->create();

    $this->actingAs($user);

    get(route('posts.edit', ['id' => $post->id]))
        ->assertForbidden();
});

test('authors can access the edit page of their post', function () {
    $user = User::factory()->create();

    $post = Post::factory()->create();

    $this->actingAs($user);

    get(route('posts.edit', ['id' => $post->id]))
        ->assertForbidden();
});

test('authors can update their posts', function ($categoryId) {
    $post = Post::factory()->create();

    $this->actingAs($post->user);

    $newTitle = str()->random(4);
    $newBody = str()->random(500);

    livewire(EditForm::class, ['postId' => $post->id])
        ->set('title', $newTitle)
        ->set('categoryId', $categoryId)
        ->set('body', $newBody)
        ->call('update')
        ->assertHasNoErrors();

    $post->refresh();

    $this->assertEquals($post->title, $newTitle);
    $this->assertEquals($post->category_id, $categoryId);
    $this->assertEquals($post->body, $newBody);
})->with([1, 2, 3]);
