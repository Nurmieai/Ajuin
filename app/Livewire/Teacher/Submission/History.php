<?php

namespace App\Livewire\Teacher\Submission;

use App\Models\Submission;
use Livewire\Component;
use Livewire\Attributes\On;

class History extends Component
{

    #[On('submission-updated')]
    public function refreshSubmissions() {}


    public function render()
    {
        $submissionsApproved = Submission::with('user')->where('status', 'approved')->latest()->get();
        $submissionsRejected = Submission::with('user')->where('status', 'rejected')->latest()->get();
        return view('livewire.teacher.submission.history', [
            'submissionsApproved' => $submissionsApproved,
            'submissionsRejected' => $submissionsRejected]
            );
    }
}
