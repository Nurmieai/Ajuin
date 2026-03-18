<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public $email, $password;

    public function login()
    {
        $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ],[
            'email.required' => 'email harus diisi',
            'password.required' => 'password harus diisi'
        ]);

        if (!Auth::validate([
            'email' => $this->email,
            'password' => $this->password
        ])) {
            $this->addError('email', 'Email atau password salah.');
            return;
        }

        $user = User::where('email', $this->email)->first();

        if (!$user->is_active) {
            $this->addError('email', 'Akun belum disetujui guru.');
            return;
        }

        // if ($user->hasRole('teacher')) {
        //     return redirect('/teacher/dashboard');
        // }
        Auth::login($user);

        $this->redirectRoute('dashboard', navigate:true);
    }

    public function render()
    {
        return view('livewire.auth.login')
        ->layout('components.layouts.login')
        ->title('Login');
    }
}
