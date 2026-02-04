<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.login')]

class Login extends Component
{
    public $email, $password;

    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt([
            'email' => $this->email,
            'password' => $this->password
        ])) {
            $this->addError('email', 'Email atau password salah.');
            return;
        }

        $user = Auth::user();

        if ($user->is_active !== '1') {
            Auth::logout();
            $this->addError('email', 'Akun belum disetujui guru.');
            return;
        }

        if ($user->hasRole('teacher')) {
            return redirect('/teacher/dashboard');
        }

        return redirect('/student/dashboard');
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
