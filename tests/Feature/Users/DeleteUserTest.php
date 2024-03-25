<?php

use App\Livewire\Pages\Users\Delete;
use App\Mail\DestroyUser;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;

use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

test('non-logged-in users cannot access the delete user page', function () {
    $user = User::factory()->create();

    get(route('users.delete', $user->id))
        ->assertStatus(302)
        ->assertRedirect(route('login'));
});

test('users can access the delete user page', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    get(route('users.delete', $user->id))
        ->assertSuccessful();
});

test('schedule the task of sending the \'delete user\' email in the queue', function () {
    Mail::fake();

    $user = User::factory()->create();

    $this->actingAs($user);

    livewire(Delete::class, ['user' => $user])
        ->call('sendDestroyEmail');

    Mail::assertQueued(DestroyUser::class);
});

test('users can delete their accounts', function () {
    $user = User::factory()->create();

    $this->assertDatabaseHas('users', ['id' => $user->id]);

    $this->actingAs($user);

    $destroyUserLink = URL::temporarySignedRoute(
        'users.destroy',
        now()->addMinutes(5),
        ['user' => $user->id]
    );

    get($destroyUserLink)->assertStatus(302);

    $this->assertDatabaseMissing('users', ['id' => $user->id]);
});

test('if the link is no longer available, users cannot delete their account', function () {
    $user = User::factory()->create();

    $this->assertDatabaseHas('users', ['id' => $user->id]);

    $this->actingAs($user);

    $destroyUserLink = URL::temporarySignedRoute(
        'users.destroy',
        now()->addMinutes(5),
        ['user' => $user->id]
    );

    // 讓時間經過 6 分鐘，使連結失效
    $this->travel(6)->minutes();

    get($destroyUserLink)->assertStatus(401);

    $this->assertDatabaseHas('users', ['id' => $user->id]);
});

// if the user has been deleted, the user's posts should also be deleted
// and user id of comments should be set to null
test('if the user has been deleted, the user\'s posts and comments should also be deleted', function () {
    $user = User::factory()->create();

    $post = Post::factory()->create(['user_id' => $user->id]);

    $comment = Comment::factory()->create(['user_id' => $user->id]);

    $this->assertDatabaseHas('users', ['id' => $user->id]);
    $this->assertDatabaseHas('posts', ['id' => $post->id, 'user_id' => $user->id]);
    $this->assertDatabaseHas('comments', ['id' => $comment->id, 'user_id' => $user->id]);

    $user->delete();

    // the posts should be deleted in the database by constraint
    // the user id of comments should be updated in the database by constraint
    $this->assertDatabaseMissing('users', ['id' => $user->id]);
    $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    $this->assertDatabaseHas('comments', ['id' => $comment->id, 'user_id' => null]);
});
