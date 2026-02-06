<?php

namespace App\Livewire\Teacher;

use App\Models\User;
use Livewire\Component;

class Activation extends Component
{

    public $students = [];

    public function mount()
    {
        $this->loadStudents();
    }

    public function loadStudents()
    {
        $this->students = User::where('is_active', false)
            ->whereHas('roles', function ($q) {
                $q->where('name', 'student');
            })
            ->get();
    }

    public function approve($Userid)
    {
        $user = User::FindOrFail($Userid);
        $user->update(['is_active' => true]);

        $this->loadStudents();

        session()->flash('success', 'Akun Siswa Berhasil Aktif');
    }

    public function render()
    {
        return view('livewire.teacher.activation');
    }
}
