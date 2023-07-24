<?php

namespace App\Http\Livewire\Auth;

use App\Providers\RouteServiceProvider;
use Livewire\Component;

class VerifyEmail extends Component
{
    public function render()
    {
        return request()->user()->hasVerifiedEmail()
            ? redirect()->intended(RouteServiceProvider::HOME)
            : view('livewire.auth.verify-email');
    }
}
