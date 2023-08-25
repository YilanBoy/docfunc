<?php

namespace App\Livewire\Auth;

use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Livewire\Attributes\Title;
use Livewire\Component;

class VerifyEmail extends Component
{
    /**
     * @return RedirectResponse|void
     */
    public function resendVerificationEmail(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(RouteServiceProvider::HOME);
        }

        $request->user()->sendEmailVerificationNotification();

        session()->flash('status', 'verification-link-sent');
    }

    #[Title('驗證電子郵件')]
    public function render()
    {
        if (request()->user()->hasVerifiedEmail()) {
            $this->redirect(RouteServiceProvider::HOME, navigate: true);
        } else {
            return view('livewire.auth.verify-email');
        }
    }
}
