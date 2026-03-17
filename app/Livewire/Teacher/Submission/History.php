<?php

namespace App\Livewire\Teacher\Submission;

use App\Models\Submission;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Attributes\On;

class History extends Component
{
    public $activeTab = 'approved';
    public $selectedSubmission;
    public $showDetailModal = false;

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    #[On('submission-updated')]
    public function refreshSubmissions() {}

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
        $this->selectedSubmission = Submission::findOrFail($submissionId);
        $this->dispatch('open-cancel-modal');
    }

    public function cancel()
    {
        if (!$this->selectedSubmission) {
            return;
        }

        try {
            $this->selectedSubmission->update([
                'status' => 'cancelled'
            ]);

            $this->reset('selectedSubmission');
            $this->dispatch('close-cancel-modal');

            session()->flash('success', 'Pengajuan berhasil dibatalkan');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            session()->flash('error', 'Terjadi kesalahan saat menolak.');
        }
    }

    public function render()
    {
        $submissions = match($this->activeTab) {
            'approved' => Submission::with('user')
                ->where('status', 'approved')
                ->latest()
                ->get(),
            'rejected' => Submission::with('user')
                ->where('status', 'rejected')
                ->latest()
                ->get(),
            'cancelled' => Submission::with('user')
                ->where('status', 'cancelled')
                ->latest()
                ->get(),
            default => collect([])
        };
        return view('livewire.teacher.submission.history', ['submissions' => $submissions]);
    }
}
