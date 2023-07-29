<?php

namespace App\Livewire\Auth;

use App\Models\User;
use App\Rules\Recaptcha;
use App\Services\SettingService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Title;
use Livewire\Component;

class Register extends Component
{
    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    public string $recaptcha = '';

    public function mount(): void
    {
        abort_if(! SettingService::isRegisterAllowed(), 503);
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'regex:/^[A-Za-z0-9\-\_]+$/u', 'between:3,25', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->mixedCase()->numbers()],
            'recaptcha' => ['required', new Recaptcha()],
        ];
    }

    protected function messages(): array
    {
        return [
            'recaptcha.required' => '請完成驗證',
        ];
    }

    /**
     * Handle an incoming registration request.
     */
    public function store()
    {
        abort_if(! SettingService::isRegisterAllowed(), 503);

        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        $this->redirect('verify-email', navigate: true);
    }

    #[Title('註冊')]
    public function render()
    {
        return view('livewire.auth.register');
    }
}
