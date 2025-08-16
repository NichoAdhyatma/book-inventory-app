<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Password;
use Livewire\Component;
use Livewire\Attributes\Rule;

class ForgotPassword extends Component
{
    #[Rule('required|email|exists:users,email')]
    public $email = '';

    public function sendResetLink()
    {
        try {
            $this->validate();

            $status = Password::sendResetLink(['email' => $this->email]);

            if ($status === Password::RESET_LINK_SENT) {
                session()->flash('success', 'Password reset link sent to your email!');
                $this->reset('email');
            } else {
                $this->addError('email', __($status));
            }
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to send reset link. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.auth.forgot-password')->layout('layouts.guest');
    }
}