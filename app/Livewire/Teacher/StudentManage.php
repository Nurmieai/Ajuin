<?php

namespace App\Livewire\Teacher;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class StudentManage extends Component
{

    use WithPagination;

    #[Url(history: true)]
    public $search = '';
    public $selectedStudent = null;
    public $showDetailModal = false;
    public $activeTab = 'active';

    // Reset halaman ke 1 setiap kali search berubah
    public function updatedSearch()
    {
        $this->resetPage();
    }

    // Reset halaman jika tab berpindah
    public function setTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    public function showDetail($studentId)
    {
        $this->selectedStudent = User::withTrashed()
            ->with(['major', 'submissions.certificates'])
            ->students()
            ->findOrFail($studentId);

        $this->showDetailModal = true;
    }

    public function closeDetail()
    {
        $this->showDetailModal = false;
        $this->reset('selectedStudent');
    }

    public function confirmDeactivate($studentId)
    {
        $this->selectedStudent = User::students()
            ->where('is_active', true)
            ->findOrFail($studentId);

        $this->dispatch('open-deactivate-modal');
    }

    public function confirmDelete($studentId)
    {
        $this->selectedStudent = User::students()
            ->findOrFail($studentId);

        $this->dispatch('open-delete-modal');
    }

    public function confirmRestore($studentId)
    {
        $this->selectedStudent = User::onlyTrashed()
            ->students()
            ->findOrFail($studentId);

        $this->dispatch('open-restore-modal');
    }

    public function confirmApprove($StudentId)
    {
        $this->selectedStudent = User::students()
            ->findOrFail($StudentId);
        $this->dispatch('open-approve-modal');
    }

    public function confirmReject($StudentId)
    {
        $this->selectedStudent = User::students()
            ->findOrFail($StudentId);
        $this->dispatch('open-reject-modal');
    }

    public function reject()
    {
        if (!$this->selectedStudent) {
            return;
        }
        try {
            $this->selectedStudent->delete();

            $this->reset('selectedStudent');
            $this->dispatch('close-reject-modal');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            session()->flash('error', 'Terjadi kesalahan: ');
        }
    }

    public function approve()
    {

        if (!$this->selectedStudent) {
            return;
        }

        try {
            $this->selectedStudent->update([
                'is_active' => true
            ]);

            $this->reset('selectedStudent');
            $this->dispatch('close-approve-modal');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            session()->flash('error', 'Terjadi kesalahan: ');
        }
    }


    public function deactivate()
    {
        if (!$this->selectedStudent) {
            return;
        }

        try {
            $this->selectedStudent->update([
                'is_active' => false
            ]);

            $this->reset('selectedStudent');
            $this->dispatch('close-deactivate-modal');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            session()->flash('error', 'Terjadi kesalahan: ');
        }
    }


    public function delete()
    {
        if (!$this->selectedStudent) {
            return;
        }

        try {
            $this->selectedStudent->delete();

            $this->reset('selectedStudent');
            $this->dispatch('close-delete-modal');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            session()->flash('error', 'Terjadi kesalahan: ');
        }
    }

    public function restore()
    {
        if (!$this->selectedStudent) {
            return;
        }

        try {
            $this->selectedStudent->restore();

            $this->reset('selectedStudent');
            $this->dispatch('close-restore-modal');

            session()->flash('success', 'Akun siswa berhasil dipulihkan dari arsip');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            session()->flash('error', 'Terjadi kesalahan: ');
        }
    }
    public function paginationView()
    {
        return 'components.ui.pagination';
    }

    public function render()
    {
        // 1. Tentukan base query berdasarkan tab
        $query = match ($this->activeTab) {
            'active' => User::students()->active(),
            'inactive' => User::students()->inactive(),
            'archived' => User::onlyTrashed()->students(),
            default => User::students(),
        };

        // 2. Tambahkan Logika Search
        $query->when($this->search, function ($q) {
            $q->where(function ($sub) {
                $sub->where('fullname', 'like', '%' . $this->search . '%')
                    ->orWhere('nisn', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        });

        return view('livewire.teacher.student-manage', [
            // Gunakan paginate agar performa tetap ringan
            'students' => $query->with('major')->latest()->paginate(10)
        ]);
    }
}
