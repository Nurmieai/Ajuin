<?php

namespace App\Livewire\Student\AcademicService\Submission;

use Livewire\Component;
use App\Models\Submission;
use Livewire\Attributes\On;

class Detail extends Component
{
    public $selectedSubmission = null;

    #[On('showDetail')]
    public function showDetail($submissionId)
    {
        // Cari data
        $this->selectedSubmission = Submission::with(['certificates', 'user'])
            ->where('user_id', auth()->id())
            ->find($submissionId);

        if ($this->selectedSubmission) {
            $this->dispatch('open-detail-modal');
        }
    }

    public function closeDetail()
    {
        $this->reset('selectedSubmission');
    }

    public function render()
    {
        return view('livewire.student.academic-service.submission.detail');
    }
}
