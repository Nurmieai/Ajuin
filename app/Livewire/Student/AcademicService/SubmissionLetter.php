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
        // $submission sudah jadi model, langsung pakai
        if ($submission->user_id !== auth()->id()) {
            session()->flash('error', 'Pengajuan tidak ditemukan.');
            $this->redirectRoute('student.submission-letter-check', navigate:true);
            return;
        }

        $this->submission = $submission;

        $this->letter = SubmissionLetterModel::where('submission_id', $this->submission->id)
            ->where('status', 'approved')
            ->latest()
            ->first();

        if (!$this->letter) {
            session()->flash('error', 'Surat belum disetujui oleh guru.');
            $this->redirectRoute('student.submission-letter-check', navigate:true);
            return;
        }
    }

    public function render()
    {
        return view('livewire.student.academic-service.submission-letter');
    }
}
