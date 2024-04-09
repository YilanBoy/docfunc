<?php

namespace App\Livewire\Pages\Auth;

use Illuminate\Support\Facades\Password;
use Illuminate\View\View;
use Livewire\Attributes\Title;
use Livewire\Component;

class ForgotPassword extends Component
{
    public string $email = '';

    protected function rules(): array
    {
        return [
            'email' => ['required', 'email'],
        ];
    }

    public function store(): void
    {
        $this->validate();

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(['email' => $this->email]);

        ($status == Password::RESET_LINK_SENT)
            ? session()->flash('status', __($status))
            : $this->addError('email', __($status));
    }

    #[Title('忘記密碼')]
    public function render(): View
    {
        return view('livewire.pages.auth.forgot-password');
    }
}
