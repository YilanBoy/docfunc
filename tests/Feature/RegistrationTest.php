<?php

use App\Http\Livewire\Layouts\Header\Nav;
use App\Models\Setting;

use function Pest\Laravel\get;
use function Pest\Laravel\post;
use function Pest\Livewire\livewire;

test('registration screen can be rendered', function () {
    $registerSetting = Setting::query()
        ->where('key', 'allow_register')
        ->firstOrFail();

    $registerSetting->update(['value' => true]);

    expect($registerSetting->value)->toBeTrue();

    get('/register')->assertStatus(200);
});

test('guest can register', function () {
    Setting::query()
        ->where('key', 'allow_register')
        ->firstOrFail()
        ->update(['value' => true]);

    $response = $this->post('/register', [
        'name' => 'Test_User',
        'email' => 'test@example.com',
        'password' => 'Password101',
        'password_confirmation' => 'Password101',
        'g-recaptcha-response' => 'fake-g-recaptcha-response',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect('verify-email');
});

test('guest can not visit register page when register is not allowed', function () {
    $registerSetting = Setting::query()
        ->where('key', 'allow_register')
        ->firstOrFail();

    expect($registerSetting->value)->toBeFalse();

    get(route('register'))->assertStatus(503);
});

test('guest can not see register button', function () {
    $registerSetting = Setting::query()
        ->where('key', 'allow_register')
        ->firstOrFail();

    expect($registerSetting->value)->toBeFalse();

    livewire(Nav::class)->assertDontSeeText('註冊');
});

test('guest can not register when register is not allowed', function () {
    $registerSetting = Setting::query()
        ->where('key', 'allow_register')
        ->firstOrFail();

    expect($registerSetting->value)->toBeFalse();

    post(route('register'), [
        'name' => 'John',
        'email' => 'John@email.com',
        'password' => 'Password01!',
        'password_confirmation' => 'Password01!',
        'g-recaptcha-response' => 'fake-g-recaptcha-response',
    ])->assertStatus(503);
});
