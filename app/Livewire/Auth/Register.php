<?php

namespace App\Livewire\Auth;

use App\Models\User;
use App\Mail\VerificationEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Attributes\Rule;

class Register extends Component
{
    #[Rule('required|string|max:255')]
    public $name = '';

    #[Rule('required|email|unique:users,email')]
    public $email = '';

    #[Rule('required|min:8|confirmed')]
    public $password = '';

    public $password_confirmation = '';

    public function register()
    {
        try {
            $this->validate();

            $verification_token = Str::random(60);

            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => $this->password,
                'verification_token' => $verification_token,
            ]);

            Mail::to($user->email)->send(new VerificationEmail($user, $verification_token));

            session()->flash('success', 'Registration successful! Please check your email to verify your account.');
            return redirect()->route('login');
        } catch (\Exception $e) {
            session()->flash('error', 'Registration failed. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.auth.register')->layout('layouts.guest');
    }
}