<?php

namespace App\Livewire\Student\AcademicService;

use App\Models\Submission;
use App\Models\SubmissionLetter;
use Livewire\Component;

class Index extends Component
{
    public $confirmingAction = null;

    public function render()
    {
        return view('livewire.student.academic-service.index');
    }

    public function cancelConfirmation()
    {
        $this->reset('confirmingAction');
    }

    public function confirmGenerate()
    {
        $user = auth()->user();

        $profileIncomplete = empty($user->fullname)
            || empty($user->nisn)
            || empty($user->major_id)
            || empty($user->gender)
            || empty($user->cv_url)
            || empty($user->portfolio_url);

        if ($profileIncomplete) {
            $this->confirmingAction = 'profile_incomplete';
            return;
        }

        $submission = Submission::where('user_id', $user->id)
            ->where('status', 'approved')
            ->latest()
            ->first();

        if (!$submission) {
            $this->confirmingAction = 'submission_pending';
            return;
        }

        $alreadyGenerated = SubmissionLetter::where('submission_id', $submission->id)->exists();

        if ($alreadyGenerated) {
            $this->confirmingAction = 'already_generated';
            return;
        }

        $this->confirmingAction = 'generate';
    }

    public function generateLetter()
    {
        $submission = Submission::where('user_id', auth()->id())
            ->where('status', 'approved')
            ->latest()
            ->first();

        if (!$submission) {
            $this->dispatch('toast', message: 'Pengajuan PKL belum disetujui.', type: 'error');
            $this->cancelConfirmation();
            return;
        }

        $alreadyGenerated = SubmissionLetter::where('submission_id', $submission->id)->exists();

        if ($alreadyGenerated) {
            $this->dispatch('toast', message: 'Surat untuk perusahaan ini sudah pernah dibuat.', type: 'error');
            $this->cancelConfirmation();
            return;
        }

        SubmissionLetter::create([
            'submission_id' => $submission->id,
            'status' => 'requested',
        ]);

        $this->cancelConfirmation();

        return $this->redirectRoute(
            'student.submission-letter',
            ['submission' => $submission->id],
            navigate: true
        );
    }

    public function redirectToProfile()
    {
        return $this->redirectRoute('student.profile', navigate: true);
    }

    public function redirectToSubmission()
    {
        return $this->redirectRoute('student.submission-manage', navigate: true);
    }

    public function redirectToLetterCheck()
    {
        return $this->redirectRoute('student.submission-letter-check', navigate: true);
    }
}
