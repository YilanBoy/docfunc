<?php

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

use function Pest\Laravel\get;
use function Pest\Laravel\put;

uses(LazilyRefreshDatabase::class);

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
