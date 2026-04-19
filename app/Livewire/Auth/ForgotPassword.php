<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;

class ForgotPassword extends Component
{
    #[Validate('required|email:rfc')]
    public $email = '';

    protected function ensureIsNotRateLimited(): void
    {
        $ipKey = 'reset-ip:' . request()->ip();
        $emailKey = 'reset-email:' . $this->email;

        if (
            RateLimiter::tooManyAttempts($ipKey, 10) ||
            RateLimiter::tooManyAttempts($emailKey, 3)
            ) {
            $seconds = max(
                RateLimiter::availableIn($ipKey), 
                RateLimiter::availableIn($emailKey)
            );

            $this->addError('email', "Terlalu banyak mencoba. Tunggu $seconds detik.");
            return;
        }
    }

    protected function incrementRateLimits(): void
    {
        RateLimiter::hit('reset-ip:' . request()->ip(), 60);
        RateLimiter::hit('reset-email:' . $this->email, 60);
    }

    public function sendResetLink()
    {
        $this->validate();

        $this->ensureIsNotRateLimited();

        $this->incrementRateLimits();

        Password::sendResetLink(['email' => $this->email]);

        session()->flash('message', 'Jika email terdaftar, link reset sudah dikirim.');

        Log ::info('password reset requested', [
            'email' => $this->email,
            'ip' => request()->ip()
        ]);

        $this->reset('email');
    }

    public function render()
    {
        return view('livewire.auth.forgot-password')
            ->layout('components.layouts.login');
    }
}