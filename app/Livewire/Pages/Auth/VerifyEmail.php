<?php

namespace App\Livewire\Pages\Auth;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
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
            return redirect()->intended();
        }

        $request->user()->sendEmailVerificationNotification();

        session()->flash('status', 'verification-link-sent');
    }

    /**
     * @return View|void
     */
    #[Title('驗證電子郵件')]
    public function render()
    {
        if (request()->user()->hasVerifiedEmail()) {
            $this->redirect('/', navigate: true);
        } else {
            return view('livewire.pages.auth.verify-email');
        }
    }
}
