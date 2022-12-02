<?php

use App\Mail\DestroyUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

use function Pest\Faker\faker;
use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Laravel\put;

uses(RefreshDatabase::class);

test('user can view own profile', function () {
    $user = User::factory()->create();

    get(route('users.index', $user->id))
        ->assertStatus(200);
});

test('guest can not visit edit page', function () {
    $user = User::factory()->create();

    get(route('users.edit', $user->id))
        ->assertStatus(302)
        ->assertRedirect(route('login'));
});

test('user can not visit others edit page', function () {
    $user = User::factory()->create();

    $otherUser = User::factory()->create();

    $this->actingAs($user)
        ->get(route('users.edit', $otherUser->id))
        ->assertStatus(403);
});

test('user can edit own information', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    // legal name
    put(route('users.update', $user->id), [
        'name' => 'New_legal_Name',
        'introduction' => faker()->realText(119),
    ])
        ->assertStatus(302)
        ->assertRedirect(route('users.index', ['user' => $user->id]));

    // illegal name
    put(route('users.update', $user->id), [
        'name' => 'New illegal Name',
        'introduction' => faker()->realText(119),
    ])
        ->assertSessionHasErrors('name');
});

test('user can visit change password page', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    get(route('users.changePassword', $user->id))
        ->assertSuccessful();
});

test('user can change password', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    put(route('users.changePassword', $user->id), [
        'current_password' => 'Password101',
        'new_password' => 'NewPassword101',
        'new_password_confirmation' => 'NewPassword101',
    ])
        ->assertStatus(302)
        ->assertSessionHas('status', '密碼修改成功！');
});

test('user can not change password with wrong current password', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    put(route('users.changePassword', $user->id), [
        'current_password' => 'WrongPassword',
        'new_password' => 'NewPassword101',
        'new_password_confirmation' => 'NewPassword101',
    ])
        ->assertSessionHasErrors('current_password');
});

test('user can not change password with invalid new password', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    put(route('users.changePassword', $user->id), [
        'current_password' => 'Password101',
        'new_password' => 'NewPassword',
        'new_password_confirmation' => 'NewPassword',
    ])
        ->assertSessionHasErrors('new_password');
});

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
