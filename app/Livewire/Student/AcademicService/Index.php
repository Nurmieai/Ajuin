<?php

namespace App\Livewire\Student\AcademicService;

use App\Models\Submission;
use App\Models\SubmissionLetter;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('livewire.student.academic-service.index');
    }

    public function confirmGenerate()
    {
        $user = auth()->user();

        $profileIncomplete = empty($user->fullname)
            || empty($user->nisn)
            || empty($user->major_id)
            || empty($user->gender)
            || empty($user->birth_date)
            || empty($user->alamat_tinggal);

        if ($profileIncomplete) {
            $this->dispatch('open-profile-warning');
            return;
        }

        $submission = Submission::where('user_id', $user->id)
            ->where('status', 'approved')
            ->latest()
            ->first();

        if (!$submission) {
            $this->dispatch('open-submission-warning');
            return;
        }

        $this->dispatch('open-generate-modal');
    }

    public function generateLetter()
    {
        $submission = Submission::where('user_id', auth()->id())
            ->where('status', 'approved')
            ->latest()
            ->first();

        if (!$submission) {
            session()->flash('error', 'Pengajuan PKL belum disetujui oleh guru.');
            $this->dispatch('close-generate-modal');
            return;
        }

        // Cek apakah sudah pernah generate
        $letter = SubmissionLetter::where('submission_id', $submission->id)
            ->latest()
            ->first();

        // Kalau belum ada, buat baru
        if (!$letter) {
            SubmissionLetter::create([
                'submission_id' => $submission->id,
                'status' => 'requested',
            ]);
        }

        $this->dispatch('close-generate-modal');

        return $this->redirectRoute(
            'student.submission-letter',
            ['submission' => $submission->id],
            navigate: true
        );
    }
}
