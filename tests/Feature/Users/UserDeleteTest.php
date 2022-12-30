<?php

use App\Mail\DestroyUser;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

use function Pest\Laravel\get;
use function Pest\Laravel\post;

uses(LazilyRefreshDatabase::class);

test('user can visit delete account page', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    get(route('users.delete', $user->id))
        ->assertSuccessful();
});

test('send destroy user email queue', function () {
    Mail::fake();

    $user = User::factory()->create();

    $this->actingAs($user);

    post(route('users.sendDestroyEmail', $user->id))
        ->assertStatus(302);

    Mail::assertQueued(DestroyUser::class);
});

test('user can delete own account', function () {
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

test('user can not delete own account if url is invalid', function () {
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
