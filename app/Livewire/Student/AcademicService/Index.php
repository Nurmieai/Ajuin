<?php

namespace App\Livewire\Student\AcademicService;

use App\Models\Submission;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('livewire.student.academic-service.index');
    }
    public $selectedSubmission;

    public function confirmGenerate()
    {
        $this->dispatch('open-generate-modal');
    }

    public function generateLetter()
    {
        $submission = \App\Models\Submission::where('user_id', auth()->id())
            ->latest()
            ->first();

        if (!$submission) {
            dd('tidak ada submission');
        }

        return $this->redirectRoute(
            'student.submission-letter',
            ['submission' => $submission->id],
            navigate: true
        );
    }
}
