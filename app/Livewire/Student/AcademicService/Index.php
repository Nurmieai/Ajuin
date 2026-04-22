<?php

namespace App\Livewire\Student\AcademicService;

use App\Models\Submission;
use App\Models\SubmissionLetter;
use Livewire\Component;

class Index extends Component
{
    // Tambahkan properti ini untuk mengontrol modal x-ui.confirmation
    public $confirmingAction = null;

    public function render()
    {
        return view('livewire.student.academic-service.index');
    }

    /**
     * Menutup modal konfirmasi
     */
    public function cancelConfirmation()
    {
        $this->reset('confirmingAction');
    }

    public function confirmGenerate()
    {
        $user = auth()->user();

        // Cek Kelengkapan Profil
        $profileIncomplete = empty($user->fullname)
            || empty($user->nisn)
            || empty($user->major_id)
            || empty($user->gender);

        if ($profileIncomplete) {
            // Set state untuk membuka modal warning profile
            $this->confirmingAction = 'profile_incomplete';
            return;
        }

        // Cek Status Pengajuan PKL
        $submission = Submission::where('user_id', $user->id)
            ->where('status', 'approved')
            ->latest()
            ->first();

        if (!$submission) {
            // Set state untuk membuka modal warning submission
            $this->confirmingAction = 'submission_pending';
            return;
        }

        // Set state untuk membuka modal konfirmasi generate
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

        $letter = SubmissionLetter::where('submission_id', $submission->id)
            ->latest()
            ->first();

        if (!$letter) {
            SubmissionLetter::create([
                'submission_id' => $submission->id,
                'status' => 'requested',
            ]);
        }

        $this->cancelConfirmation();

        return $this->redirectRoute(
            'student.submission-letter',
            ['submission' => $submission->id],
            navigate: true
        );
    }

    /**
     * Method bantuan untuk tombol di dalam modal warning
     */
    public function redirectToProfile()
    {
        return $this->redirectRoute('student.profile', navigate: true);
    }

    public function redirectToSubmission()
    {
        return $this->redirectRoute('student.submission-manage', navigate: true);
    }
}
