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

    // Properti baru untuk mengontrol modal konfirmasi
    public $confirmingAction = null;

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

    public function cancelConfirmation()
    {
        $this->reset('confirmingAction');
    }

    public function showDetail($submissionId)
    {
        $this->selectedSubmission = Submission::with(['user', 'certificates'])
            ->findOrFail($submissionId);

        $this->dispatch('open-teacher-detail-modal');
    }

    public function closeDetail()
    {
        $this->reset(['selectedSubmission', 'confirmingAction']);
    }

    public function confirmCancel($submissionId)
    {
        $this->selectedSubmission = Submission::with('user')->findOrFail($submissionId);
        $this->confirmingAction = 'cancel'; // Memicu modal x-ui.confirmation
    }

    public function cancel()
    {
        if (!$this->selectedSubmission) return;

        try {
            $this->selectedSubmission->update(['status' => 'cancelled']);

            // Reset semua state setelah berhasil
            $this->reset(['selectedSubmission', 'confirmingAction']);

            // Gunakan event dispatch untuk UI Toast (Bukan Session Flash)
            $this->dispatch('success', 'Pengajuan berhasil dibatalkan');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            $this->dispatch('error', 'Terjadi kesalahan saat membatalkan pengajuan.');
        }
    }

    public function paginationView()
    {
        return 'components.ui.pagination';
    }

    public function render()
    {
        // Tentukan base query berdasarkan tab
        $query = Submission::with('user')->where('status', $this->activeTab);

        // Logika Search
        $query->when($this->search, function ($q) {
            $q->where(function ($sub) {
                $sub->whereHas('user', function ($userQuery) {
                    $userQuery->where('fullname', 'like', '%' . $this->search . '%');
                })->orWhere('company_name', 'like', '%' . $this->search . '%');
            });
        });

        return view('livewire.teacher.submission.history', [
            'submissions' => $query->latest()->paginate(10)
        ]);
    }
}
