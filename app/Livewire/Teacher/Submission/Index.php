<?php

namespace App\Livewire\Teacher\Submission;

use App\Models\Submission;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Attributes\On;

class Index extends Component
{
    public $selectedSubmission = null;
    public $showDetailModal = false;

    #[On('submission-updated')]
    public function refreshSubmissions()
    {

    }

    public function confirmApprove($submissionId)
    {
        $this->selectedSubmission = Submission::findOrFail($submissionId);
        $this->dispatch('open-approve-modal');
    }

    public function confirmReject($submissionId)
    {
        $this->selectedSubmission = Submission::findOrFail($submissionId);
        $this->dispatch('open-reject-modal');
    }

    public function approve()
    {
        if (!$this->selectedSubmission) {
            return;
        }

        try {
            $this->selectedSubmission->update([
                'status' => 'approved',
                'approved_at' => now()
            ]);

            $this->reset('selectedSubmission');
            $this->dispatch('close-approve-modal');
            
            session()->flash('success', 'Pengajuan berhasil diterima');
            
        } catch (\Exception $e) {
            Log::error($e);
            session()->flash('error', 'Terjadi kesalahan: ');
        }
    }

    public function reject()
    {
        if (!$this->selectedSubmission) {
            return;
        }

        try {
            $this->selectedSubmission->update([
                'status' => 'rejected'
            ]);

            $this->reset('selectedSubmission');
            $this->dispatch('close-reject-modal');
            
            session()->flash('success', 'Pengajuan berhasil ditolak');
            
        } catch (\Exception $e) {
            Log::error($e);
            session()->flash('error', 'Terjadi kesalahan: ');
        }
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

    public function render()
    {
        $submissions = Submission::with('user')
            ->where('submission_type', 'mandiri')
            ->where('status', 'submitted')
            ->latest()
            ->get();

        return view('livewire.teacher.submission.index', [
            'submissions' => $submissions
        ]);
    }
}