<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use Livewire\Attributes\Rule;

class EditProfile extends Component
{
    #[Rule('required|string|max:255')]
    public $name = '';

    #[Rule('required|email|unique:users,email')]
    public $email = '';

    public function mount()
    {
        $user = auth()->user();
        $this->name = $user->name;
        $this->email = $user->email;
        
        // Update validation rule to exclude current user
        $this->getRules()['email'] = 'required|email|unique:users,email,' . $user->id;
    }

    public function getRules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
        ];
    }

    public function updateProfile()
    {
        try {
            $this->validate($this->getRules());

            $user = auth()->user();
            $email_changed = $user->email !== $this->email;
            
            $user->update([
                'name' => $this->name,
                'email' => $this->email,
                'email_verified_at' => $email_changed ? null : $user->email_verified_at,
            ]);

            if ($email_changed) {
                $user->sendEmailVerificationNotification();
                session()->flash('info', 'Profile updated! Please verify your new email address.');
            } else {
                session()->flash('success', 'Profile updated successfully!');
            }

        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update profile. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.profile.edit-profile');
    }
}