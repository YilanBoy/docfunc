<?php

use App\Livewire\Pages\Auth\Register;
use App\Livewire\Shared\Layouts\Header;
use App\Models\Setting;
use App\Models\User;

use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

beforeEach(function () {
    Setting::query()
        ->where('key', 'allow_register')
        ->firstOrFail()
        ->update(['value' => true]);
});

test('registration screen can be rendered', function () {
    $registerSetting = Setting::query()
        ->where('key', 'allow_register')
        ->firstOrFail();

    expect($registerSetting->value)->toBeTrue();

    get('/register')->assertStatus(200);
});

test('guest can register', function () {
    livewire(Register::class)
        ->set('name', 'Test User')
        ->set('email', 'test@example.com')
        ->set('password', 'Password101')
        ->set('password_confirmation', 'Password101')
        ->set('captchaToken', 'fake-captcha-response')
        ->call('store')
        ->assertRedirect('/verify-email');

    $this->assertAuthenticated();

    $this->assertDatabaseHas('users', [
        'name' => 'Test User',
        'email' => 'test@example.com',
    ]);
});

test('name is required', function () {
    livewire(Register::class)
        ->set('name', '')
        ->set('email', 'test@example.com')
        ->set('password', 'Password101')
        ->set('password_confirmation', 'Password101')
        ->set('captchaToken', 'fake-captcha-response')
        ->call('store')
        ->assertHasErrors(['name' => 'required']);
});

// name must be unique
test('name must be unique', function () {
    $user = User::factory()->create([
        'name' => 'Test User',
    ]);

    livewire(Register::class)
        ->set('name', $user->name)
        ->set('email', 'test@example.com')
        ->set('password', 'Password101')
        ->set('password_confirmation', 'Password101')
        ->set('captchaToken', 'fake-captcha-response')
        ->call('store')
        ->assertHasErrors(['name' => 'unique']);
});

test('the number of characters in the name must be between 3 and 25.', function (string $name) {
    livewire(Register::class)
        ->set('name', $name)
        ->set('email', 'test@example.com')
        ->set('password', 'Password101')
        ->set('password_confirmation', 'Password101')
        ->set('captchaToken', 'fake-captcha-response')
        ->call('store')
        ->assertHasErrors(['name' => 'between']);
})->with([
    'ya',
    'ThisIsAVeryLongNameThatExceedsTheMaximumNumberOfCharacters',
]);

// name must be alphanumeric, '-' and '_'
test('name must be alphanumeric, \'-\' and \'_\'', function (string $name) {
    livewire(Register::class)
        ->set('name', $name)
        ->set('email', 'test@example.com')
        ->set('password', 'Password101')
        ->set('password_confirmation', 'Password101')
        ->set('captchaToken', 'fake-captcha-response')
        ->call('store')
        ->assertHasErrors(['name' => 'regex']);
})->with([
    'Allen@', 'Allen$', 'Allen#', 'Allen%', 'Allen^',
    'Allen&', 'Allen*', 'Allen(', 'Allen)', 'Allen=',
    'Allen+', 'Allen[', 'Allen]', 'Allen{', 'Allen}',
    'Allen|', 'Allen\\', 'Allen:', 'Allen;', 'Allen"',
    'Allen\'', 'Allen<', 'Allen>', 'Allen,', 'Allen.',
    'Allen?', 'Allen/', 'Allen~', 'Allen`', 'Allen!',
]);

// name input will be trimmed
test('name input will be trimmed', function () {
    livewire(Register::class)
        ->set('name', ' Test User ')
        ->set('email', 'test@example.com')
        ->set('password', 'Password101')
        ->set('password_confirmation', 'Password101')
        ->set('captchaToken', 'fake-captcha-response')
        ->call('store');

    $this->assertDatabaseHas('users', [
        'name' => 'Test User',
    ]);
});

test('email is required', function () {
    livewire(Register::class)
        ->set('name', 'Test User')
        ->set('email', '')
        ->set('password', 'Password101')
        ->set('password_confirmation', 'Password101')
        ->set('captchaToken', 'fake-captcha-response')
        ->call('store')
        ->assertHasErrors(['email' => 'required']);
});

test('email must be valid', function () {
    livewire(Register::class)
        ->set('name', 'Test User')
        ->set('email', 'wrongEmail')
        ->set('password', 'Password101')
        ->set('password_confirmation', 'Password101')
        ->set('captchaToken', 'fake-captcha-response')
        ->call('store')
        ->assertHasErrors(['email' => 'email']);
});

test('email must be unique', function () {
    $user = User::factory()->create([
        'name' => 'Test User',
        'email' => 'test@example.com',
    ]);

    livewire(Register::class)
        ->set('name', 'Test User 2')
        ->set('email', $user->email)
        ->set('password', 'Password101')
        ->set('password_confirmation', 'Password101')
        ->set('captchaToken', 'fake-captcha-response')
        ->call('store')
        ->assertHasErrors(['email' => 'unique']);
});

test('password is required', function () {
    livewire(Register::class)
        ->set('name', 'Test User')
        ->set('email', 'test@example.com')
        ->set('password', '')
        ->set('password_confirmation', 'Password101')
        ->set('captchaToken', 'fake-captcha-response')
        ->call('store')
        ->assertHasErrors(['password' => 'required']);
});

test('password must be confirmed', function () {
    livewire(Register::class)
        ->set('name', 'Allen')
        ->set('email', 'test@example.com')
        ->set('password', 'Password101')
        ->set('password_confirmation', 'Password102')
        ->set('captchaToken', 'fake-captcha-response')
        ->call('store')
        ->assertHasErrors(['password' => 'confirmed']);
});

test('password must be at least 8 characters, mixed case, numbers and letters', function (string $password) {
    livewire(Register::class)
        ->set('name', 'Allen')
        ->set('email', 'test@example.com')
        ->set('password', $password)
        ->set('password_confirmation', $password)
        ->set('captchaToken', 'fake-captcha-response')
        ->call('store')
        ->assertHasErrors();
})->with([
    'password', 'PASSWORD', 'Password', 'password101',
    'PASSWORD101', '12345678', 'abcdefgh',
]);

test('guest can not visit register page when register is not allowed', function () {
    Setting::query()
        ->where('key', 'allow_register')
        ->firstOrFail()
        ->update(['value' => false]);

    get(route('register'))->assertStatus(503);
});

test('guest can not see register button', function () {
    Setting::query()
        ->where('key', 'allow_register')
        ->firstOrFail()
        ->update(['value' => false]);

    livewire(Header::class)->assertDontSeeText('註冊');
});
