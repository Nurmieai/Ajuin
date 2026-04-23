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

    public $confirmingAction = null;

    public function updatedSearch()
    {
        $this->resetPage();
    }

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

            if ($this->selectedSubmission->submission_type === 'mitra') {
                $partner = $this->selectedSubmission->partner;
                if($partner){
                    $partner->increment('quota');
                }
                }

            $this->reset(['selectedSubmission', 'confirmingAction']);

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
