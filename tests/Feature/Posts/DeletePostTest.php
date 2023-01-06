<?php


use App\Http\Livewire\Users\Posts\DeletedPostCard;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('author can soft delete own post', function () {
    $user = User::factory()->create();

    $post = Post::factory()->create([
        'title' => 'This is a test post title',
        'user_id' => $user->id,
        'category_id' => 1,
    ]);

    $this->actingAs($user)
        ->delete(route('posts.destroy', $post->id))
        ->assertStatus(302)
        ->assertRedirect(route('users.index', ['user' => $user->id, 'tab' => 'posts']));

    $this->assertSoftDeleted('posts', ['id' => $post->id]);
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

    Livewire::test(DeletedPostCard::class, ['post' => $post])
        ->call('restore', $post->id)
        ->assertRedirect(route('users.index', ['user' => $user->id, 'tab' => 'posts']));

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

    Livewire::test(DeletedPostCard::class, ['post' => $post])
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
