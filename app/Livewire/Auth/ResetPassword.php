<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Attributes\Rule;
use App\Models\User;

class ResetPassword extends Component
{
    public $token;
    
    #[Rule('required|email|exists:users,email')]
    public $email = '';

    #[Rule('required|min:8|confirmed')]
    public $password = '';

    public $password_confirmation = '';

    public function mount($token)
    {
        $this->token = $token;
        $this->email = request()->email ?? '';
    }

    public function resetPassword()
    {
        try {
            $this->validate();

            $status = Password::reset(
                [
                    'email' => $this->email,
                    'password' => $this->password,
                    'password_confirmation' => $this->password_confirmation,
                    'token' => $this->token
                ],
                function (User $user, string $password) {
                    $user->forceFill([
                        'password' => $password
                    ])->setRememberToken(Str::random(60));

                    $user->save();

                    event(new PasswordReset($user));
                }
            );

            if ($status === Password::PASSWORD_RESET) {
                session()->flash('success', 'Password has been reset successfully!');
                return redirect()->route('login');
            }

            $this->addError('email', __($status));
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to reset password. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.auth.reset-password')->layout('layouts.guest');
    }
}