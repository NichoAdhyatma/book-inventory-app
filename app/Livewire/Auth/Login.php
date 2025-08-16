<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Rule;

class Login extends Component
{
    #[Rule('required|email')]
    public $email = '';

    #[Rule('required|min:8')]
    public $password = '';

    #[Rule('boolean')]
    public $remember = false;

    public function login()
{
    try {
        $this->validate();

        $user = \App\Models\User::where('email', $this->email)->first();

        if (!$user) {
            $this->addError('email', 'Email is not registered.');
            return;
        }

        if (!$user->hasVerifiedEmail()) {
            $this->addError('email', 'Email has not been verified.');
            return;
        }


        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            session()->regenerate();
            return redirect()->intended('/home');
        }

        $this->addError('email', 'The provided credentials do not match our records.');
    } catch (\Exception $e) {
        session()->flash('error', 'An error occurred during login. Please try again.');
    }
}


    public function render()
    {
        return view('livewire.auth.login')->layout('layouts.guest');
    }
}