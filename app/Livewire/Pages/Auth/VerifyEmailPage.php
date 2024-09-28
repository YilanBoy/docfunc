<?php

namespace App\Livewire\Pages\Auth;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Livewire\Attributes\Title;
use Livewire\Component;

class VerifyEmailPage extends Component
{
    public function mount(): void
    {
        if (request()->user()->hasVerifiedEmail()) {
            $this->redirect('/', navigate: true);
        }
    }

    public function resendVerificationEmail(Request $request): void
    {
        $request->user()->sendEmailVerificationNotification();

        session()->flash('status', 'verification-link-sent');
    }

    #[Title('驗證電子郵件')]
    public function render(): View
    {
        return view('livewire.pages.auth.verify-email-page');
    }
}
