<?php

namespace App\Livewire\Student\AcademicService;

use Livewire\Component;
use App\Models\Submission;

class SubmissionLetter extends Component
{
    public Submission $submission;

    public function mount(Submission $submission)
    {
        $this->submission = Submission::where('user_id', auth()->id())
            ->where('status', 'approved')
            ->latest()
            ->first();

        if (!$this->submission) {
            session()->flash('error', 'Pengajuan belum disetujui.');
            return redirect()->route('student.submission-manage');
        }
        $this->submission = $submission;
    }

    public function render()
    {
        return view('livewire.student.academic-service.submission-letter');
    }
}
