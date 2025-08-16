<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use Livewire\Attributes\Rule;
use Livewire\Component;

class Register extends Component
{
    #[Rule('required|string|min:3')]
    public $name = '';

    #[Rule('required|email|unique:users,email')]
    public $email = '';

    #[Rule('required|string|min:6')]
    public $password = '';

    #[Rule('required|same:password')]
    public $password_confirmation = '';

    #[Rule('accepted')]
    public $terms = false;

    public function register()
    {
        $this->validate();

        try {
            $verification_token = Str::random(60);

            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => $this->password,
                'verification_token' => $verification_token,
            ]);

            $user->sendEmailVerificationNotification();

            session()->flash('success', 'Registration successful! Check your email to verify.');
            return redirect()->route('login');
        } catch (\Exception $e) {      
            session()->flash('error', 'Registration failed. Please try again. ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.auth.register')->layout('layouts.guest');
    }
}