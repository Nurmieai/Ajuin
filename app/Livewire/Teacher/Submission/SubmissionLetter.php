<?php

namespace App\Livewire\Teacher\Submission;

use App\Models\Submission;
use Livewire\Component;

class SubmissionLetter extends Component
{
    public $selectedSubmission;

    public function showDetail($id)
    {
        return $this->redirectRoute(
            'teacher.teacher-submission-letter-detail',
            ['id' => $id],
            navigate: true
        );
    }

    public function confirmApprove($id)
    {
        $this->selectedSubmission = Submission::with('user')->find($id);

        $this->dispatch('open-approve-modal');
    }

    public function approve()
    {
        if (!$this->selectedSubmission) return;

        $this->selectedSubmission->update([
            'status' => 'approved'
        ]);

        $this->dispatch('close-approve-modal');

        session()->flash('success', 'Pengajuan berhasil diterima.');
    }

    public function confirmReject($id)
    {
        $this->selectedSubmission = Submission::with('user')->find($id);

        $this->dispatch('open-reject-modal');
    }

    public function reject()
    {
        if (!$this->selectedSubmission) return;

        $this->selectedSubmission->update([
            'status' => 'rejected'
        ]);

        $this->dispatch('close-reject-modal');

        session()->flash('success', 'Pengajuan berhasil ditolak.');
    }


    public function render()
    {
        $submissions = Submission::with('user')
            ->latest()
            ->paginate(10);

        return view('livewire.teacher.submission.submission-letter', [
            'submissions' => $submissions
        ]);
    }
}
