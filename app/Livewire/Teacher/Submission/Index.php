<?php

namespace App\Livewire\Teacher\Submission;

use App\Models\Submission;
use Livewire\Component;

class Index extends Component
{
    public $submissions = [];
    public $selectedSubmissionId = null;

    public function mount ()
    {
        $this->loadSubmissions();
    }

    public function loadSubmissions ()
    {
        $this->submissions = Submission::with('user')->where('submission_type', 'mandiri')->where('status', 'submitted')->latest()->get();
    }   

    public function confirmApprove($submissionsId)
    {
        $this->selectedSubmissionId =  $submissionsId;
        $this->dispatch('open-approve-modal');
    }

    public function confirmReject($submissionsId)
    {
        $this->selectedSubmissionId =  $submissionsId;
        $this->dispatch('open-reject-modal');
    }

    public function approve()
    {
        if (!$this->selectedSubmissionId ) {
            return;
        }

        Submission::findOrFail($this->selectedSubmissionId)->update(['status' => 'approved']);

        $this->selectedSubmissionId = null;
        $this->loadSubmissions();

        session()->flash('success', 'Pengajuan berhasil diterima');
    }

    public function reject()
    {
        if (!$this->selectedSubmissionId ) {
            return;
        }

        Submission::findOrFail($this->selectedSubmissionId)->update(['status' => 'rejected']);

        $this->selectedSubmissionId = null;
        $this->loadSubmissions();

        session()->flash('success', 'Pengajuan berhasil ditolak');
    }

    public function render()
    {
        return view('livewire.teacher.submission.index');
    }
}
