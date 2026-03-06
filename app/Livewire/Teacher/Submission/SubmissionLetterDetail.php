<?php

namespace App\Livewire\Teacher\Submission;

use App\Models\Submission;
use Livewire\Component;

class SubmissionLetterDetail extends Component
{
    public $submission;

    public function mount($id)
    {
        $this->submission = Submission::with('user')
            ->findOrFail($id);
    }

    public function render()
    {
        return view('livewire.teacher.submission.submission-letter-detail');
    }
}
