<?php

namespace App\Livewire\Teacher\Submission;

use App\Models\Submission;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class History extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public $search = '';

    #[Url(history: true)]
    public $activeTab = 'approved';

    public $selectedSubmission = null;
    public $showDetailModal = false;

    // Reset halaman jika kata kunci pencarian berubah
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

    public function showDetail($submissionId)
    {
        $this->selectedSubmission = Submission::with(['user', 'certificates'])
            ->findOrFail($submissionId);
        $this->showDetailModal = true;
    }

    public function closeDetail()
    {
        $this->showDetailModal = false;
        $this->reset('selectedSubmission');
    }

    public function confirmCancel($submissionId)
    {
        $this->selectedSubmission = Submission::with('user')->findOrFail($submissionId);
        $this->dispatch('open-cancel-modal');
    }

    public function cancel()
    {
        if (!$this->selectedSubmission) return;

        try {
            $this->selectedSubmission->update(['status' => 'cancelled']);
            $this->reset('selectedSubmission');
            $this->dispatch('close-cancel-modal');
            session()->flash('success', 'Pengajuan berhasil dibatalkan');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            session()->flash('error', 'Terjadi kesalahan saat membatalkan pengajuan.');
        }
    }

    public function paginationView()
    {
        // Menggunakan komponen custom pagination yang sama dengan StudentManage
        return 'components.ui.pagination';
    }

    public function render()
    {
        // 1. Tentukan base query berdasarkan tab
        $query = Submission::with('user')
            ->where('status', $this->activeTab);

        // 2. Tambahkan Logika Search
        $query->when($this->search, function ($q) {
            $q->where(function ($sub) {
                $sub->whereHas('user', function ($userQuery) {
                    $userQuery->where('fullname', 'like', '%' . $this->search . '%');
                })
                    ->orWhere('company_name', 'like', '%' . $this->search . '%');
            });
        });

        return view('livewire.teacher.submission.history', [
            'submissions' => $query->latest()->paginate(10)
        ]);
    }
}
