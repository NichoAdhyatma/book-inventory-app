<?php

namespace App\Livewire\Profile;

use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\Attributes\Rule;

class ChangePassword extends Component
{
    #[Rule('required|current_password')]
    public $current_password = '';

    #[Rule('required|min:8|different:current_password')]
    public $new_password = '';

    #[Rule('required|same:new_password')]
    public $new_password_confirmation = '';

    public function updatePassword()
    {
        try {
            $this->validate();

            auth()->user()->update([
                'password' => $this->new_password
            ]);

            $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
            session()->flash('success', 'Password updated successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update password. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.profile.change-password');
    }
}