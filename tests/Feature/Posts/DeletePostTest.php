<?php

use App\Http\Livewire\Posts\Partials\DesktopShowMenu;
use App\Http\Livewire\Posts\Partials\MobileShowMenu;
use App\Http\Livewire\UserInfoPage\PostsByYear;
use App\Models\Post;
use App\Models\User;
use Livewire\Livewire;

test('author can soft delete own post in desktop show post page', function () {
    $post = Post::factory()->create();

    $this->actingAs(User::find($post->user_id));

    Livewire::test(DesktopShowMenu::class, [
        'postId' => $post->id,
        'postTitle' => $post->title,
        'authorId' => $post->user_id,
    ])
        ->call('deletePost', $post->id)
        ->assertRedirect(route('users.index', ['user' => $post->user_id, 'tab' => 'posts']));

    $this->assertSoftDeleted('posts', ['id' => $post->id]);
});

test('guest cannot delete others\' post in desktop show post page', function () {
    $post = Post::factory()->create();

    Livewire::test(DesktopShowMenu::class, [
        'postId' => $post->id,
        'postTitle' => $post->title,
        'authorId' => $post->user_id,
    ])
        ->call('deletePost', $post->id)
        ->assertForbidden();

    $this->assertNotSoftDeleted('posts', ['id' => $post->id]);
});

test('user cannot delete others\' post in desktop show post page', function () {
    $user = User::factory()->create();

    $post = Post::factory()->create();

    $this->actingAs($user);

    Livewire::test(DesktopShowMenu::class, [
        'postId' => $post->id,
        'postTitle' => $post->title,
        'authorId' => $post->user_id,
    ])
        ->call('deletePost', $post->id)
        ->assertForbidden();

    $this->assertNotSoftDeleted('posts', ['id' => $post->id]);
});

test('author can soft delete own post in mobile show post page', function () {
    $post = Post::factory()->create();

    $this->actingAs(User::find($post->user_id));

    Livewire::test(MobileShowMenu::class, ['postId' => $post->id])
        ->call('deletePost', $post->id)
        ->assertRedirect(route('users.index', ['user' => $post->user_id, 'tab' => 'posts']));

    $this->assertSoftDeleted('posts', ['id' => $post->id]);
});

test('guest cannot delete others\' post in mobile show post page', function () {
    $post = Post::factory()->create();

    Livewire::test(MobileShowMenu::class, ['postId' => $post->id])
        ->call('deletePost', $post->id)
        ->assertForbidden();

    $this->assertNotSoftDeleted('posts', ['id' => $post->id]);
});

test('user cannot delete others\' post in mobile show post page', function () {
    $user = User::factory()->create();

    $post = Post::factory()->create();

    $this->actingAs($user);

    Livewire::test(MobileShowMenu::class, ['postId' => $post->id])
        ->call('deletePost', $post->id)
        ->assertForbidden();

    $this->assertNotSoftDeleted('posts', ['id' => $post->id]);
});

test('author can soft delete own post in user information post card', function () {
    $post = Post::factory()->create();

    $this->actingAs(User::find($post->user_id));

    Livewire::test(PostsByYear::class, [
        'posts' => [$post],
        'userId' => $post->user_id,
        'year' => $post->created_at->format('Y'),
    ])
        ->call('deletePost', $post->id)
        ->assertDispatchedBrowserEvent('info-badge', ['status' => 'success', 'message' => '文章已刪除']);

    $this->assertSoftDeleted('posts', ['id' => $post->id]);
});

test('guest cannot delete others\' post in user information post card', function () {
    $post = Post::factory()->create();

    Livewire::test(PostsByYear::class, [
        'posts' => [$post],
        'userId' => $post->user_id,
        'year' => $post->created_at->format('Y'),
    ])
        ->call('deletePost', $post->id)
        ->assertForbidden();

    $this->assertNotSoftDeleted('posts', ['id' => $post->id]);
});

test('user cannot delete others\' post in user information post card', function () {
    $user = User::factory()->create();

    $post = Post::factory()->create();

    $this->actingAs($user);

    Livewire::test(PostsByYear::class, [
        'posts' => [$post],
        'userId' => $post->user_id,
        'year' => $post->created_at->format('Y'),
    ])
        ->call('deletePost', $post->id)
        ->assertForbidden();

    $this->assertNotSoftDeleted('posts', ['id' => $post->id]);
});

test('author can restore deleted post', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $post = Post::factory()->create([
        'title' => 'This is a test post title',
        'user_id' => $user->id,
        'category_id' => 1,
        'deleted_at' => now(),
    ]);

    $this->assertSoftDeleted('posts', ['id' => $post->id]);

    Livewire::test(PostsByYear::class, [
        'posts' => [$post],
        'userId' => $post->user_id,
        'year' => $post->created_at->format('Y'),
    ])
        ->call('restore', $post->id)
        ->assertDispatchedBrowserEvent('info-badge', ['status' => 'success', 'message' => '文章已恢復']);

    $this->assertNotSoftDeleted('posts', ['id' => $post->id]);
});

test('users cannot restore other users\' post', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $author = User::factory()->create();

    $post = Post::factory()->create([
        'title' => 'This is a test post title',
        'user_id' => $author->id,
        'category_id' => 1,
        'deleted_at' => now(),
    ]);

    Livewire::test(PostsByYear::class, [
        'posts' => [$post],
        'userId' => $user->id,
        'year' => $post->created_at->format('Y'),
    ])
        ->call('restore', $post->id)
        ->assertForbidden();

    $this->assertSoftDeleted('posts', ['id' => $post->id]);
});

test('prune the stale post', function () {
    $user = User::factory()->create();

    Post::factory()->create([
        'title' => 'This is a stale post',
        'user_id' => $user->id,
        'category_id' => 1,
        'deleted_at' => now()->subDays(31),
    ]);

    Post::factory()->create([
        'title' => 'This is a normal post',
        'user_id' => $user->id,
        'category_id' => 1,
    ]);

    $this->artisan('model:prune');

    $this->assertDatabaseCount('posts', 1);
    $this->assertDatabaseHas('posts', [
        'title' => 'This is a normal post',
        'category_id' => 1,
    ]);
});
