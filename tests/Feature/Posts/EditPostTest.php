<?php

use App\Livewire\Pages\Posts\Edit;
use App\Livewire\Shared\Users\PostsGroupByYear;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use App\Services\ContentService;

use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

test('visitors cannot access the edit pages of other people\'s post', function () {
    $post = Post::factory()->create();

    get(route('posts.edit', ['post' => $post->id]))
        ->assertRedirect(route('login'));
});

test('authors can access the edit page of their post', function () {
    $post = Post::factory()->create();

    $this->actingAs($post->user);

    get(route('posts.edit', ['post' => $post->id]))
        ->assertSuccessful();
});

test('users cannot access the edit page of other people\'s post', function () {
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
    $newPrivateStatus = (bool) rand(0, 1);

    $newTagCollection = Tag::factory()->count(3)->create();

    $newTagsJson = $newTagCollection
        ->map(fn ($item) => ['id' => $item->id, 'name' => $item->name])
        ->toJson();

    livewire(Edit::class, ['post' => $post])
        ->set('form.title', $newTitle)
        ->set('form.category_id', $categoryId)
        ->set('form.tags', $newTagsJson)
        ->set('form.body', $newBody)
        ->set('form.is_private', $newPrivateStatus)
        ->call('update')
        ->assertHasNoErrors();

    $post->refresh();

    $contentService = app(ContentService::class);

    $newTagIdsArray = $newTagCollection
        ->map(fn ($item) => $item->id)
        ->all();

    expect($post)
        ->title->toBe($newTitle)
        ->slug->toBe($contentService->makeSlug($newTitle))
        ->category_id->toBe($categoryId)
        ->body->toBe($newBody)
        ->excerpt->toBe($contentService->makeExcerpt($newBody))
        ->is_private->toBe($newPrivateStatus)
        ->and($post->tags->pluck('id')->toArray())->toBe($newTagIdsArray);
})->with('defaultCategoryIds');

test('users can update the private status of their posts in user info page', function ($privateStatus) {
    $post = Post::factory()->create([
        'is_private' => $privateStatus,
        'created_at' => now(),
    ]);

    $this->actingAs($post->user);

    livewire(PostsGroupByYear::class, [
        'year' => now()->year,
        'userId' => $post->user_id,
        'posts' => $post->all(),
    ])
        ->call('postPrivateToggle', $post->id)
        ->assertHasNoErrors()
        ->assertDispatched(
            'info-badge',
            status: 'success',
            // if original status is true, then the message should be '文章狀態已切換為公開'
            // because the status is toggled to false
            message: $privateStatus ? '文章狀態已切換為公開' : '文章狀態已切換為私人',
        );

    $post->refresh();

    expect($post->is_private)->toBe(! $privateStatus);
})->with([true, false]);
