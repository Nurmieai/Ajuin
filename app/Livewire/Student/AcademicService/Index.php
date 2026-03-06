<?php

namespace App\Livewire\Student\AcademicService;

use App\Models\Submission;
use Livewire\Component;

class Index extends Component
{
    public $selectedSubmission;

    public function render()
    {
        return view('livewire.student.academic-service.index');
    }

    public function confirmGenerate()
    {
        $this->dispatch('open-generate-modal');
    }

    public function generateLetter()
    {
        $submission = Submission::where('user_id', auth()->id())
            ->latest()
            ->first();

        if (!$submission) {
            session()->flash('error', 'Tidak ada pengajuan ditemukan.');
            return;
        }

        if ($submission->letter_generated) {
            return $this->redirectRoute(
                'student.submission-letter',
                ['submission' => $submission->id],
                navigate: true
            );
        }

        $submission->update([
            'letter_generated' => true
        ]);

        return $this->redirectRoute(
            'student.submission-letter',
            ['submission' => $submission->id],
            navigate: true
        );
    }
}
