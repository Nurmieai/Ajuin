<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ChangePassword extends Component
{
    public $old_password;
    public $password;
    public $password_confirmation;

    public function update()
    {
        $this->validate([
            'old_password' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        if (!Hash::check($this->old_password, Auth::user()->password)) {
            $this->addError('old_password', 'Password lama tidak sesuai');
            return;
        }

        Auth::user()->update([
            'password' => Hash::make($this->password),
        ]);

        $this->reset();
        $this->dispatch('password-updated');
    }

    public function render()
    {
        return view('livewire.change-password');
    }
}
