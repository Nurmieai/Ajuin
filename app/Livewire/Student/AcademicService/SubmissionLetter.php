<?php

namespace App\Livewire\Student\AcademicService;

use Livewire\Component;
use App\Models\Submission;
use App\Models\SubmissionLetter as SubmissionLetterModel;

class SubmissionLetter extends Component
{
    public ?Submission $submission = null;
    public ?SubmissionLetterModel $letter = null;

    public function mount(Submission $submission): void
    {
        if ($submission->user_id !== auth()->id()) {
            $this->dispatch('toast', message: 'Pengajuan tidak ditemukan.', type: 'error');
            $this->redirectRoute('student.submission-letter-check', navigate: true);
            return;
        }

        $this->submission = $submission;

        $this->letter = SubmissionLetterModel::where('submission_id', $this->submission->id)
            ->where('status', 'approved')
            ->latest()
            ->first();

        if (!$this->letter) {
            $this->dispatch('toast', message: 'Surat belum disetujui oleh guru.', type: 'error');
            $this->redirectRoute('student.submission-letter-check', navigate: true);
            return;
        }
    }

    public function render()
    {
        return view('livewire.student.academic-service.submission-letter');
    }
}
