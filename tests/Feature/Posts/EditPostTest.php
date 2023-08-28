<?php

use App\Livewire\EditPostPage;
use App\Livewire\UserInfoPage\PostsByYear;
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

    livewire(EditPostPage::class, ['post' => $post])
        ->set('form.title', $newTitle)
        ->set('form.category_id', $categoryId)
        ->set('form.body', $newBody)
        ->call('update')
        ->assertHasNoErrors();

    $post->refresh();

    $this->assertEquals($post->title, $newTitle);
    $this->assertEquals($post->category_id, $categoryId);
    $this->assertEquals($post->body, $newBody);
})->with('defaultCategoryIds');

test('users can update the private status of their posts.', function ($privateStatus) {
    $post = Post::factory()->create([
        'is_private' => $privateStatus,
        'created_at' => now(),
    ]);

    $this->actingAs($post->user);

    livewire(PostsByYear::class, [
        'year' => now()->year,
        'userId' => $post->user_id,
        'posts' => $post->get(),
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
