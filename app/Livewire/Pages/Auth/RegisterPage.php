<?php

namespace App\Livewire\Pages\Auth;

use App\Models\User;
use App\Rules\Captcha;
use App\Services\SettingService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Livewire\Attributes\Title;
use Livewire\Component;

class RegisterPage extends Component
{
    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    public string $captchaToken = '';

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'regex:/^[A-Za-z0-9\-\_\s]+$/u', 'between:3,25', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->mixedCase()->numbers()],
            'captchaToken' => ['required', new Captcha],
        ];
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(): void
    {
        abort_if(! SettingService::isRegisterAllowed(), 503);

        $this->validate();

        $user = User::create([
            'name' => trim($this->name),
            'email' => $this->email,
            'password' => $this->password,
        ]);

        event(new Registered($user));

        Auth::login($user);

        $this->redirect('verify-email', navigate: true);
    }

    #[Title('註冊')]
    public function render(): View
    {
        return view('livewire.pages.auth.register-page');
    }
}
