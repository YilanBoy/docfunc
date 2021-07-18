<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Rules\Recaptcha;
use Illuminate\Validation\Rules\Password;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \App\Http\Requests\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $passwordRule = Password::min(8)->letters()->mixedCase()->numbers();

        $rules = [
            'name' => 'required|string|regex:/^[A-Za-z0-9\-\_]+$/u|between:3,25|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', $passwordRule],
        ];

        $messages = [];

        if (app()->isProduction()) {
            $rules['g-recaptcha-response'] = ['required', new Recaptcha()];
            $messages['g-recaptcha-response.required'] = '請完成驗證';
        }

        $request->validate($rules, $messages);

        Auth::login($user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]));

        event(new Registered($user));

        return redirect('verify-email');
    }
}
