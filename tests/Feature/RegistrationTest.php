<?php

use App\Livewire\Auth\Register;
use App\Livewire\Layouts\Header;
use App\Models\Setting;

use function Pest\Laravel\get;
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
    // register is not allowed by default, so we need to change the setting
    Setting::query()
        ->where('key', 'allow_register')
        ->firstOrFail()
        ->update(['value' => true]);

    Livewire::test(Register::class)
        ->set('name', 'Test_User')
        ->set('email', 'test@example.com')
        ->set('password', 'Password101')
        ->set('password_confirmation', 'Password101')
        ->set('recaptcha', 'fake-g-recaptcha-response')
        ->call('store')
        ->assertRedirect('/verify-email');

    $this->assertAuthenticated();
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

    livewire(Header::class)->assertDontSeeText('註冊');
});
