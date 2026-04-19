<?php

namespace App\Livewire\Auth;

use App\Models\Major;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Livewire\Component;

class Register extends Component
{
    public $fullname;
    public $username;
    public $email;
    public $password;
    public $password_confirmation;
    public $nisn;
    public $major_id;
    
  public function register()
    {
        $this->validate([   
            'fullname' => 'required|string|min:3|max:100',
            'username' => 'required|unique:users|string|min:3|max:100|alpha_dash',
            'email' => 'required|email:rfc|unique:users|lowercase',
            'password' => 'required|min:6|confirmed',
            'nisn' => 'required|unique:users,nisn|digits:10',
            'major_id' => 'required|exists:majors,id',
        ],[
            'fullname.required' => 'Nama Lengkap harus diisi',
            'fullname.max' => 'Nama Lengkap maksimal 100 karakter',
            'username.alpha_dash' => 'Username mengandung karakter yang dilarang',
            'username.min' => 'Username minimal 3 karakter',
            'username.string' => 'username tidak valid',
            'username.required' => 'Username harus diisi',
            'username.unique' => 'username sudah dipakai',
            'email.required' => 'email harus diisi',
            'email.unique' => 'email sudah dipakai',
            'password.required' => 'password harus diisi',
            'password.min' => 'password minimal 6 karakter',
            'password.confirmed' => 'Konfirmasi Password tidak sesuai',
            'nisn.required' => 'nisn harus diisi',
            'nisn.unique' => 'nisn sudah dipakai',
            'nisn.digits' => 'nisn tidak valid',
            'major_id.required' => 'pilih jurusan'
        ]);

        $ipKey = 'register-ip' . request()->ip();
        $emailKey = 'register-email' . Str::lower($this->email);

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

        $user = User::create([
            'fullname' => $this->fullname,
            'username' => $this->username,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'nisn' => $this->nisn,
            'major_id' => $this->major_id,
            'is_active' => false,
        ]); 

        $user->assignRole('student');

        session()->flash('message', 'Akun menunggu persetujuan guru.');

        $this->redirectRoute('login', navigate:true);
    }

    public function render()
    {
        return view('livewire.auth.register', [
            'majors' => Major::select('id', 'name')->get()
        ])
        ->layout('components.layouts.login');
    }
}
