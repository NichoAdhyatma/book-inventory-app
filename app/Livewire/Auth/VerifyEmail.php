<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Livewire\Component;

class VerifyEmail extends Component
{
    public $message;
    public $is_success = false;

    public function mount($id, $hash)
    {
        try {
            $user = User::findOrFail($id);

            if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
                $this->message = 'Invalid verification link.';
                return;
            }

            if ($user->hasVerifiedEmail()) {
                $this->message = 'Email already verified!';
                $this->is_success = true;
                return;
            }

            if ($user->markEmailAsVerified()) {
                $this->message = 'Email verified successfully!';
                $this->is_success = true;
            }
        } catch (\Exception $e) {
            $this->message = 'Invalid verification link.';
        }
    }

    public function render()
    {
        return view('livewire.auth.verify-email', [
            'message' => $this->message,
            'is_success' => $this->is_success,
        ])->layout('layouts.guest');
    }
}