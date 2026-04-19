<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Livewire\Component;

class Login extends Component
{
    public $email, $password;

    public function login()
    {
        $this->validate([
            'email' => 'required|email:rfc',
            'password' => 'required'
        ],[
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Email tidak valid',
            'password.required' => 'Password wajib diisi'
        ]);

        $ipKey = 'login-ip' . request()->ip();
        $emailKey = 'login-email' . Str::lower($this->email);

        if (
            RateLimiter::tooManyAttempts($ipKey, 10) ||
            RateLimiter::tooManyAttempts($emailKey, 5)
        ) {
            $seconds = max(
                RateLimiter::availableIn($ipKey),
                RateLimiter::availableIn($emailKey)
            );

            $this->addError('email', "Terlalu banyak mencoba tunggu $seconds detik.");
            return;
        }

        RateLimiter::hit($ipKey, 600);
        RateLimiter::hit($emailKey, 600);

        $credentials = [
            'email' => $this->email,
            'password' => $this->password,
            'is_active' => true,
        ];

        if (Auth::attempt($credentials)) {
            RateLimiter::clear($ipKey, 600);
            RateLimiter::clear($emailKey, 600);

            session()->regenerate();

            return $this->redirectRoute('dashboard', navigate: true);
        }

        // usleep(random_int(100000, 300000));
        $this->addError('email', 'Email atau password salah');
    }

    public function render()
    {
        return view('livewire.auth.login')
        ->layout('components.layouts.login')
        ->title('Login');
    }
}
