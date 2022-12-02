<?php

use App\Http\Livewire\Layouts\Header\Nav;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Livewire\livewire;

uses(RefreshDatabase::class);

test('registration screen can be rendered', function () {
    Setting::query()->forceCreate([
        'name' => '開放註冊',
        'key' => 'allow_register',
        'value' => 'true',
    ]);

    get('/register')->assertStatus(200);
});

test('guest can register', function () {
    Setting::query()->forceCreate([
        'name' => '開放註冊',
        'key' => 'allow_register',
        'value' => 'true',
    ]);

    $response = $this->post('/register', [
        'name' => 'Test_User',
        'email' => 'test@example.com',
        'password' => 'Password101',
        'password_confirmation' => 'Password101',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect('verify-email');
});

test('guest can not visit register page when register is not allowed', function () {
    Setting::query()->forceCreate([
        'name' => '開放註冊',
        'key' => 'allow_register',
        'value' => 'false',
    ]);

    get(route('register'))->assertStatus(503);
});

test('guest can not see register button', function () {
    Setting::query()->forceCreate([
        'name' => '開放註冊',
        'key' => 'allow_register',
        'value' => 'false',
    ]);

    livewire(Nav::class)->assertDontSeeText('註冊');
});

test('guest can not register when register is not allowed', function () {
    Setting::query()->forceCreate([
        'name' => '開放註冊',
        'key' => 'allow_register',
        'value' => 'false',
    ]);

    post(route('register'), [
        'name' => 'John',
        'email' => 'John@email.com',
        'password' => 'Password01!',
        'password_confirmation' => 'Password01!',
    ])->assertStatus(503);
});
