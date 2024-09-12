<?php

namespace App\Livewire\Pages\Auth;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

class ResetPasswordPage extends Component
{
    // token will be passed in the URL
    // and auto binding will take care of it
    public string $token = '';

    // email will be passed in the URL query string
    #[Url]
    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    public function mount(string $token): void
    {
        $this->token = $token;
    }

    protected function rules(): array
    {
        return [
            'token' => 'required',
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
        ];
    }

    /**
     * Handle an incoming new password request.
     */
    public function store(): void
    {
        $this->validate();

        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise, we will parse the error and return the response.
        $status = Password::reset(
            [
                'email' => $this->email,
                'password' => $this->password,
                'password_confirmation' => $this->password_confirmation,
                'token' => $this->token,
            ],
            function ($user) {
                $user->forceFill([
                    'password' => Hash::make($this->password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
        if ($status == Password::PASSWORD_RESET) {
            $this->dispatch('info-badge', status: 'success', message: '密碼修改成功！請重新登入');

            $this->redirect(route('login'), navigate: true);
        }

        $this->addError('email', __($status));
    }

    #[Title('重設密碼')]
    public function render(): View
    {
        return view('livewire.pages.auth.reset-password-page');
    }
}
