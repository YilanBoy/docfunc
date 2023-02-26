<?php

use App\Models\User;

use function Pest\Laravel\get;
use function Pest\Laravel\post;

test('login screen can be rendered', function () {
    get('/login')->assertStatus(200);
});

test('users can authenticate using the login screen', function () {
    $user = User::factory()->create();

    post('/login', [
        'email' => $user->email,
        'password' => 'Password101',
        'g-recaptcha-response' => 'fake-g-recaptcha-response',
    ])->assertRedirect('/');

    $this->assertAuthenticated();
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
        'g-recaptcha-response' => 'fake-g-recaptcha-response',
    ]);

    $this->assertGuest();
});
