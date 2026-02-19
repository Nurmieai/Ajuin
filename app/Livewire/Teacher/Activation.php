<?php

namespace App\Livewire\Teacher;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class Activation extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public $search = '';

    public $selectedUserId = null;

    public function updatedSearch()
    {
        $this->resetPage();
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

        session()->flash('success', 'Akun Siswa Berhasil Aktif');
    }

    public function render()
    {
        $students = User::where('is_active', false)
            ->whereHas('roles', function ($q) {
                $q->where('name', 'student');
            })
            ->when($this->search, function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->where('fullname', 'like', '%' . $this->search . '%')
                        ->orWhere('nisn', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->latest()
            ->paginate(10);

        return view('livewire.teacher.activation', [
            'students' => $students
        ]);
    }
}
