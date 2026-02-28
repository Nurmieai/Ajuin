<?php

namespace App\Livewire\Student;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Profile extends Component
{
    public $fullname;
    public $nisn;
    public $major_id;
    public $class;
    public $phone;
    public $address;

    public function mount()
    {
        $user = Auth::user();

        $this->fullname = $user->fullname;
        $this->nisn = $user->nisn;
        $this->major_id = $user->major_id;
        $this->class = $user->class;
        $this->phone = $user->phone;
        $this->address = $user->address;
    }

    public function save()
    {
        $this->validate([
            'fullname' => 'required',
            'class' => 'required',
            'phone' => 'required',
            'address' => 'required',
        ]);

        Auth::user()->update([
            'fullname' => $this->fullname,
            'class' => $this->class,
            'phone' => $this->phone,
            'address' => $this->address,
        ]);

        session()->flash('success', 'Profil berhasil diperbarui.');
    }
    
    public function render()
    {
        return view('livewire.student.profile');
    }
}
