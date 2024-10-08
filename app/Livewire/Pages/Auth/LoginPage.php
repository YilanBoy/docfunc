<?php

namespace App\Livewire\Pages\Auth;

use App\Rules\Captcha;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Livewire\Attributes\Title;
use Livewire\Component;

class LoginPage extends Component
{
    public string $email = '';

    public string $password = '';

    public bool $remember = false;

    public string $captchaToken = '';

    protected function rules(): array
    {
        return [
            'email' => 'required|string|email',
            'password' => 'required|string',
            'captchaToken' => ['required', new Captcha],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     */
    private function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     */
    private function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    private function throttleKey(): string
    {
        return Str::lower($this->email).'|'.request()->ip();
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(): void
    {
        $this->validate();

        $this->authenticate();

        session()->regenerate();

        $this->dispatch('info-badge', status: 'success', message: '登入成功！');

        $this->redirect('/', navigate: true);
    }

    #[Title('登入')]
    public function render(): View
    {
        return view('livewire.pages.auth.login-page');
    }
}
