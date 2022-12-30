<?php

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

use function Pest\Faker\faker;
use function Pest\Laravel\get;
use function Pest\Laravel\put;

uses(LazilyRefreshDatabase::class);

test('guest can not visit edit page', function () {
    $user = User::factory()->create();

    get(route('users.edit', $user->id))
        ->assertStatus(302)
        ->assertRedirect(route('login'));
});

test('user can visit own edit page', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    get(route('users.edit', $user->id))
        ->assertStatus(200);
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
