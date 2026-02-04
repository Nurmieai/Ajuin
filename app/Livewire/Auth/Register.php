<?php

namespace App\Livewire\Auth;

use App\Models\Major;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Register extends Component
{
    public $fullname;
    public $username;
    public $email;
    public $password;
    public $nisn;
    public $major_id;
    
  public function register()
    {
        $this->validate([   
            'fullname' => 'required',
            'username' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'nisn' => 'required|unique:users',
            'major_id' => 'required',
        ],[
            'fullname.required' => 'Full name harus diisi',
            'username.required' => 'Username harus diisi',
            'username.unique' => 'username sudah dipakai',
            'email.required' => 'email harus diisi',
            'email.unique' => 'email sudah dipakai',
            'password.required' => 'password harus diisi',
            'password.min' => 'password minimal 6 karakter',
            'nisn.required' => 'nisn harus diisi',
            'nisn.unique' => 'nisn sudah dipakai',
            'major_id.required' => 'pilih jurusan'
        ]);

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

        return redirect('/login');
    }

    public function render()
    {
        return view('livewire.auth.register', [
            'majors' => Major::all()
        ]);
    }
}
