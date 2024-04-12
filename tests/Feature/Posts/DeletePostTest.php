<?php

use App\Livewire\Shared\Posts\MobileMenu;
use App\Livewire\Shared\Posts\SideMenu;
use App\Livewire\Shared\Users\PostsGroupByYear;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;

use function Pest\Livewire\livewire;

test('author can soft delete own post in desktop show post page', function () {
    $post = Post::factory()->create();

    $this->actingAs(User::find($post->user_id));

    livewire(SideMenu::class, [
        'postId' => $post->id,
        'postTitle' => $post->title,
        'authorId' => $post->user_id,
    ])
        ->call('deletePost', $post->id)
        ->assertDispatched('info-badge', status: 'success', message: '成功刪除文章！')
        ->assertRedirect(route('users.show', ['user' => $post->user_id, 'tab' => 'posts']));

    $this->assertSoftDeleted('posts', ['id' => $post->id]);
});

test('guest cannot delete others\' post in desktop show post page', function () {
    $post = Post::factory()->create();

    livewire(SideMenu::class, [
        'postId' => $post->id,
        'postTitle' => $post->title,
        'authorId' => $post->user_id,
    ])
        ->call('deletePost', $post->id)
        ->assertForbidden();

    $this->assertNotSoftDeleted('posts', ['id' => $post->id]);
});

test('user cannot delete others\' post in desktop show post page', function () {
    $post = Post::factory()->create();

    // Login as another user
    loginAsUser();

    livewire(SideMenu::class, [
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

    loginAsUser(User::find($post->user_id));

    livewire(MobileMenu::class, ['postId' => $post->id])
        ->call('destroy', $post->id)
        ->assertDispatched('info-badge', status: 'success', message: '成功刪除文章！')
        ->assertRedirect(route('users.show', ['user' => $post->user_id, 'tab' => 'posts']));

    $this->assertSoftDeleted('posts', ['id' => $post->id]);
});

test('guest cannot delete others\' post in mobile show post page', function () {
    $post = Post::factory()->create();

    livewire(MobileMenu::class, ['postId' => $post->id])
        ->call('destroy', $post->id)
        ->assertForbidden();

    $this->assertNotSoftDeleted('posts', ['id' => $post->id]);
});

test('user cannot delete others\' post in mobile show post page', function () {
    $post = Post::factory()->create();

    loginAsUser();

    livewire(MobileMenu::class, ['postId' => $post->id])
        ->call('destroy', $post->id)
        ->assertForbidden();

    $this->assertNotSoftDeleted('posts', ['id' => $post->id]);
});

test('author can soft delete own post in user information post card', function () {
    $post = Post::factory()->create();

    loginAsUser(User::find($post->user_id));

    livewire(PostsGroupByYear::class, [
        'posts' => [$post],
        'userId' => $post->user_id,
        'year' => $post->created_at->format('Y'),
    ])
        ->call('deletePost', $post->id)
        ->assertDispatched('info-badge', status: 'success', message: '文章已刪除');

    $this->assertSoftDeleted('posts', ['id' => $post->id]);
});

test('guest cannot delete others\' post in user information post card', function () {
    $post = Post::factory()->create();

    livewire(PostsGroupByYear::class, [
        'posts' => [$post],
        'userId' => $post->user_id,
        'year' => $post->created_at->format('Y'),
    ])
        ->call('deletePost', $post->id)
        ->assertForbidden();

    $this->assertNotSoftDeleted('posts', ['id' => $post->id]);
});

test('user cannot delete others\' post in user information post card', function () {
    $post = Post::factory()->create();

    loginAsUser();

    livewire(PostsGroupByYear::class, [
        'posts' => [$post],
        'userId' => $post->user_id,
        'year' => $post->created_at->format('Y'),
    ])
        ->call('deletePost', $post->id)
        ->assertForbidden();

    $this->assertNotSoftDeleted('posts', ['id' => $post->id]);
});

test('author can restore deleted post', function () {
    $user = loginAsUser();

    $post = Post::factory()->create([
        'title' => 'This is a test post title',
        'user_id' => $user->id,
        'category_id' => 1,
        'deleted_at' => now(),
    ]);

    $this->assertSoftDeleted('posts', ['id' => $post->id]);

    livewire(PostsGroupByYear::class, [
        'posts' => [$post],
        'userId' => $post->user_id,
        'year' => $post->created_at->format('Y'),
    ])
        ->call('restore', $post->id)
        ->assertDispatched('info-badge', status: 'success', message: '文章已恢復');

    $this->assertNotSoftDeleted('posts', ['id' => $post->id]);
});

test('users cannot restore other users\' post', function () {
    $user = loginAsUser();

    $author = User::factory()->create();

    $post = Post::factory()->create([
        'title' => 'This is a test post title',
        'user_id' => $author->id,
        'category_id' => 1,
        'deleted_at' => now(),
    ]);

    livewire(PostsGroupByYear::class, [
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

// if the post has been deleted, the post's comments should also be deleted
test('if the post has been deleted, the post\'s comments should also be deleted', function () {
    $user = User::factory()->create();

    $post = Post::factory()->create(['user_id' => $user->id]);

    $comment = Comment::factory()->create(['user_id' => $user->id, 'post_id' => $post->id]);

    $this->assertDatabaseHas('posts', ['id' => $post->id]);
    $this->assertDatabaseHas('comments', ['id' => $comment->id]);

    $post->forceDelete();

    $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
});
