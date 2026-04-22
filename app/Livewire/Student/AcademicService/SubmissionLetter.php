<?php

namespace App\Livewire\Student\AcademicService;

use Livewire\Component;
use App\Models\Submission;
use App\Models\SubmissionLetter as SubmissionLetterModel;

class SubmissionLetter extends Component
{
    public ?Submission $submission = null;
    public ?SubmissionLetterModel $letter = null;
    public $groupSubmissions;

    public function mount(Submission $submission): void
    {
        if ($submission->user_id !== auth()->id()) {
            $this->dispatch('toast', message: 'Pengajuan tidak ditemukan.', type: 'error');
            $this->redirectRoute('student.submission-letter-check', navigate: true);
            return;
        }

        $this->submission = $submission->load(['user.major']);

        $this->letter = SubmissionLetterModel::where('submission_id', $this->submission->id)
            ->where('status', 'approved')
            ->latest()
            ->first();

        if (!$this->letter) {
            $this->dispatch('toast', message: 'Surat belum disetujui oleh guru.', type: 'error');
            $this->redirectRoute('student.submission-letter-check', navigate: true);
            return;
        }

        // Ambil semua siswa dalam grup yang sama
        if ($this->submission->partner_id) {
            $this->groupSubmissions = Submission::with(['user.major'])
                ->where('partner_id', $this->submission->partner_id)
                ->where('status', 'approved')
                ->whereHas('letters', fn($q) => $q->where('status', 'approved'))
                ->oldest()
                ->get();
        } else {
            $this->groupSubmissions = Submission::with(['user.major'])
                ->whereNull('partner_id')
                ->where('company_name', $this->submission->company_name)
                ->whereDate('start_date', $this->submission->start_date)
                ->whereDate('finish_date', $this->submission->finish_date)
                ->where('status', 'approved')
                ->whereHas('letters', fn($q) => $q->where('status', 'approved'))
                ->oldest()
                ->get();
        }

        // Fallback
        if ($this->groupSubmissions->isEmpty()) {
            $this->groupSubmissions = collect([$this->submission]);
        }
    }

    public function render()
    {
        return view('livewire.student.academic-service.submission-letter', [
            'submission'       => $this->submission,
            'letter'           => $this->letter,
            'groupSubmissions' => $this->groupSubmissions,
        ]);
    }
}
