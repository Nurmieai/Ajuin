<?php

namespace App\Livewire\Student\AcademicService;

use Livewire\Component;
use App\Models\Submission;
use App\Models\SubmissionLetter as SubmissionLetterModel;

class SubmissionLetterCheck extends Component
{
    public ?Submission $submission = null;
    public ?SubmissionLetterModel $letter = null;

    public function mount(): void
    {
        $this->submission = Submission::where('user_id', auth()->id())
            ->latest()
            ->first();

        if ($this->submission?->status === 'approved') {
            $this->letter = SubmissionLetterModel::where('submission_id', $this->submission->id)
                ->latest()
                ->first();
        }
    }

    public function requestLetter(): void
    {
        if (!$this->submission || $this->submission->status !== 'approved') {
            // Ubah dari session()->flash ke dispatch event toast
            $this->dispatch('toast', message: 'Pengajuan harus disetujui terlebih dahulu.', type: 'error');
            return;
        }

        $existing = SubmissionLetterModel::where('submission_id', $this->submission->id)
            ->whereIn('status', ['requested', 'approved'])
            ->exists();

        if ($existing) {
            // Ubah dari session()->flash ke dispatch event toast
            $this->dispatch('toast', message: 'Surat sudah pernah diajukan.', type: 'error');
            return;
        }

        $this->letter = SubmissionLetterModel::create([
            'submission_id' => $this->submission->id,
            'status' => 'requested',
        ]);

        // Ubah dari session()->flash ke dispatch event toast
        $this->dispatch('toast', message: 'Permintaan surat berhasil dikirim.', type: 'success');
    }

    public function render()
    {
        return view('livewire.student.academic-service.submission-letter-check');
    }
}
