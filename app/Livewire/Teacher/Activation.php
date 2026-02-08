<?php

namespace App\Livewire\Teacher;

use App\Models\User;
use Livewire\Component;

class Activation extends Component
{

    public $students = [];
    public $selectedUserId = null;

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

    public function confirmApprove($userId)
    {
        $this->selectedUserId = $userId;

        $this->dispatch('open-approve-modal');
    }

    public function approve()
    {
        
        if (!$this->selectedUserId) {
            return;
        }

        User::findOrFail($this->selectedUserId)
            ->update(['is_active' => true]);

        $this->selectedUserId = null;
        $this->loadStudents();

        session()->flash('success', 'Akun Siswa Berhasil Aktif');
    }   

    public function render()
    {
        return view('livewire.teacher.activation');
    }
}
