<?php

use App\Livewire\Auth\Login;
use App\Models\User;

use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

test('login screen can be rendered', function () {
    get('/login')->assertStatus(200);
});

test('users can authenticate using the login screen', function () {
    $user = User::factory()->create();

    // use request() will cause livewire tests fail
    // https://github.com/livewire/livewire/issues/936
    livewire(Login::class)
        ->set('email', $user->email)
        ->set('password', 'Password101')
        ->set('recaptcha', 'fake-g-recaptcha-response')
        ->call('store')
        ->assertRedirect('/');

    $this->assertAuthenticated();
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    livewire(Login::class)
        ->set('email', $user->email)
        ->set('password', 'wrongPassword')
        ->set('recaptcha', 'fake-g-recaptcha-response')
        ->call('store');

    $this->assertGuest();
});
