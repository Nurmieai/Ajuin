<?php

namespace App\Livewire\Teacher;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class StudentManage extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public $search = '';
    public ?User $selectedStudent = null;
    public $showDetailModal = false;
    public bool $isDetailOpen = false;
    public $activeTab = 'active';

    // State untuk kontrol Modal Konfirmasi
    public bool $isDeactivateOpen = false;
    public bool $isApproveOpen = false;
    public bool $isRejectOpen = false;

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    public function showDetail($studentId)
    {
        $this->selectedStudent = User::with(['major', 'submissions' => function ($q){
            $q->latest()->limit(5)->withCount('certificates');
        }])
            ->students()
            ->findOrFail($studentId);

        $this->isDetailOpen = true;
        $this->dispatch('open-student-detail-modal');
    }

    public function closeDetail()
    {
        $this->isDetailOpen = false;
        $this->reset('selectedStudent');
        $this->dispatch('close-student-detail-modal');
    }

    public function confirmDeactivate($studentId)
    {
        $this->selectedStudent = User::students()->where('is_active', true)->findOrFail($studentId);
        $this->isDeactivateOpen = true;
    }

    public function confirmApprove($studentId)
    {
        $this->selectedStudent = User::students()->findOrFail($studentId);
        $this->isApproveOpen = true;
    }

    public function confirmReject($studentId)
    {
        $this->selectedStudent = User::students()->findOrFail($studentId);
        $this->isRejectOpen = true;
    }

    public function cancelConfirmation()
    {
        $this->isDeactivateOpen = false;
        $this->isApproveOpen = false;
        $this->isRejectOpen = false;
        $this->reset('selectedStudent');
    }

    // --- Actions (Eksekusi Data) ---

    public function reject()
    {
        if (!$this->selectedStudent) return;

        try {
            $name = $this->selectedStudent->fullname;
            $this->selectedStudent->delete();

            $this->cancelConfirmation();
            $this->dispatch('toast', message: "Akun $name berhasil dihapus.", type: 'success');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            $this->dispatch('toast', message: 'Gagal menghapus akun.', type: 'error');
        }
    }

    public function approve()
    {
        if (!$this->selectedStudent) return;

        try {
            $name = $this->selectedStudent->fullname;
            $this->selectedStudent->update(['is_active' => true]);

            $this->cancelConfirmation();
            $this->dispatch('toast', message: "Akun $name berhasil diaktifkan.", type: 'success');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            $this->dispatch('toast', message: 'Gagal mengaktifkan akun.', type: 'error');
        }
    }

    public function deactivate()
    {
        if (!$this->selectedStudent) return;

        try {
            DB::transaction(function () {

            $this->selectedStudent->update(['is_active' => false]);

            $this->selectedStudent->submissions()->update(['status' => 'cancelled']);
            });
            $name = $this->selectedStudent->fullname;
            $this->cancelConfirmation();
            $this->dispatch('toast', message: "Akun $name berhasil dinonaktifkan.", type: 'warning');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            $this->dispatch('toast', message: 'Gagal menonaktifkan akun.', type: 'error');
        }
    }


    public function restore()
    {
        if (!$this->selectedStudent) return;

        try {
            $name = $this->selectedStudent->fullname;
            $this->selectedStudent->restore();

            $this->cancelConfirmation();
            $this->dispatch('toast', message: "Akun $name berhasil dipulihkan.", type: 'success');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            $this->dispatch('toast', message: 'Gagal memulihkan akun.', type: 'error');
        }
    }

    public function paginationView()
    {
        return 'components.ui.pagination';
    }

    public function render()
    {
        $query = match ($this->activeTab) {
            'active' => User::students()->active(),
            'inactive' => User::students()->inactive(),
            default => User::students(),
        };

        $query->when($this->search, function ($q) {
            $q->where(function ($sub) {
                $sub->where('fullname', 'like', '%' . $this->search . '%')
                    ->orWhere('nisn', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        });

        $query->with(['major'])
        ->withCount([
            'submissions as has_approved_submissions' => function ($q) {
                $q->where('status', 'approved');
            }
        ]);

        return view('livewire.teacher.student-manage', [
            'students' => $query->latest()->paginate(10)
        ]);
    }
}
