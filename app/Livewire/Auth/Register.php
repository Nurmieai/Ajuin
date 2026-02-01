<?php

namespace App\Livewire\Auth;

use App\Models\Major;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]

class Register extends Component
{
//     public $fullname;
//     public $username;
//     public $email;
//     public $password;
//     public $nisn;
//     public $major_id;
    
//   public function register()
//     {
//         $this->validate([
//             'fullname' => 'required',
//             'username' => 'required|unique:users',
//             'email' => 'required|email|unique:users',
//             'password' => 'required|min:6',
//             'nisn' => 'required|unique:users',
//             'major_id' => 'required',
//         ]);

//         $user = User::create([
//             'fullname' => $this->fullname,
//             'username' => $this->username,
//             'email' => $this->email,
//             'password' => Hash::make($this->password),
//             'nisn' => $this->nisn,
//             'major_id' => $this->major_id,
//             'status' => 'pending',
//         ]);

//         $user->assignRole('siswa');

//         session()->flash('message', 'Akun menunggu persetujuan guru.');

//         return redirect('/login');
//     }

    public function render()
    {
        return view('livewire.auth.register', [
            'majors' => Major::all()
        ]);
    }
}
