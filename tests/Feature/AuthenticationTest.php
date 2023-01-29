<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\get;
use function Pest\Laravel\post;

uses(RefreshDatabase::class);

beforeEach(function () {
    $fakeResponse = [
        'success' => true,
        'score' => 1,
    ];

    Http::fake([
        'https://www.google.com/recaptcha/api/siteverify' => Http::response($fakeResponse),
    ]);
});

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
