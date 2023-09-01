<?php

use App\Livewire\Pages\Auth\ForgotPassword;
use App\Livewire\Pages\Auth\ResetPassword as ResetPasswordComponent;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Notification;

use function Pest\Laravel\get;
use function Pest\Livewire\livewire;

test('reset password link screen can be rendered', function () {
    get('/forgot-password')
        ->assertSeeLivewire(ForgotPassword::class)
        ->assertStatus(200);
});

test('reset password link can be requested', function () {
    Notification::fake();

    $user = User::factory()->create();

    livewire(ForgotPassword::class)
        ->set('email', $user->email)
        ->call('store')
        ->assertHasNoErrors();

    Notification::assertSentTo($user, ResetPassword::class);
});

test('reset password screen can be rendered, but url must be correct', function () {
    Notification::fake();

    $user = User::factory()->create();

    Password::sendResetLink(['email' => $user->email]);

    Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
        get(route('password.reset', [
            'token' => $notification->token,
            'email' => $user->email,
        ]))
            ->assertSeeLivewire(ResetPasswordComponent::class)
            ->assertStatus(200);

        return true;
    });
});

test('password can be reset with valid token', function () {
    Notification::fake();
    Event::fake();

    $user = User::factory()->create();

    Password::sendResetLink(['email' => $user->email]);

    Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
        Livewire::withQueryParams(['email' => $user->email])
            ->test(ResetPasswordComponent::class, ['token' => $notification->token])
            ->set('password', 'Banana101!')
            ->set('password_confirmation', 'Banana101!')
            ->call('store');

        Event::assertDispatched(PasswordReset::class);

        return true;
    });

    expect(Hash::check('Banana101!', $user->fresh()->password))->toBeTrue();
});
