<?php

namespace App\Livewire\Teacher\Submission;

use App\Models\Submission;
use App\Models\SubmissionLetter as SubmissionLetterModel;
use Livewire\Component;

class SubmissionLetterGroup extends Component
{
    public string $groupKey = '';
    public $letters = [];
    public $companyLabel = null;

    public bool $isApproveOpen = false;
    public bool $isRejectOpen  = false;

    public $selectedSubmission = null;
    public $selectedLetter     = null;

    private function groupKey(Submission $submission): string
    {
        if ($submission->partner_id) {
            return 'partner_' . $submission->partner_id;
        }

        return 'manual_'
            . $submission->company_name . '_'
            . \Carbon\Carbon::parse($submission->start_date)->format('Ymd') . '_'
            . \Carbon\Carbon::parse($submission->finish_date)->format('Ymd');
    }

    public function mount(string $groupKey): void
    {
        $this->groupKey = $groupKey;
        $this->loadLetters();
    }

    public function loadLetters(): void
    {
        $latestLetterIds = SubmissionLetterModel::selectRaw('MAX(id) as id')
            ->groupBy('submission_id')
            ->pluck('id')
            ->toArray();

        $allLetters = SubmissionLetterModel::with(['submission.user'])
            ->whereIn('id', $latestLetterIds)
            ->oldest()
            ->get();

        $filtered = $allLetters->filter(
            fn($l) => $this->groupKey($l->submission) === $this->groupKey
        )->values();

        $this->letters      = $filtered->toArray();
        $this->companyLabel = $filtered->first()?->submission?->company_name;
    }

    public function confirmApprove($letterId): void
    {
        $this->selectedLetter     = SubmissionLetterModel::with('submission.user')->find($letterId);
        $this->selectedSubmission = $this->selectedLetter?->submission;
        $this->isApproveOpen      = true;
    }

    public function approve(): void
    {
        if (!$this->selectedLetter) return;

        $this->selectedLetter->update([
            'status'      => 'approved',
            'approved_at' => now(),
        ]);

        Submission::where('id', $this->selectedLetter->submission_id)
            ->update([
                'status'      => 'approved',
                'approved_at' => now(),
            ]);

        $name = $this->selectedSubmission?->user?->fullname;
        $this->cancelConfirmation();
        $this->loadLetters();

        $this->dispatch('toast', message: 'Surat ' . $name . ' berhasil diterima.', type: 'success');
    }

    // ===================== REJECT =====================

    public function confirmReject($letterId): void
    {
        $this->selectedLetter     = SubmissionLetterModel::with('submission.user')->find($letterId);
        $this->selectedSubmission = $this->selectedLetter?->submission;
        $this->isRejectOpen       = true;
    }

    public function reject(): void
    {
        if (!$this->selectedLetter) return;

        $submissionId = $this->selectedLetter->submission_id;
        $name         = $this->selectedSubmission?->user?->fullname;

        $this->selectedLetter->delete();

        Submission::where('id', $submissionId)->update(['status' => 'submitted']);

        $this->cancelConfirmation();
        $this->loadLetters();

        $this->dispatch('toast', message: 'Surat ' . $name . ' ditolak. Siswa perlu mengajukan ulang.', type: 'error');
    }

    public function cancelConfirmation(): void
    {
        $this->isApproveOpen      = false;
        $this->isRejectOpen       = false;
        $this->selectedLetter     = null;
        $this->selectedSubmission = null;
    }

    // ===================== RENDER =====================

    public function render()
    {
        return view('livewire.teacher.submission.submission-letter-group', [
            'letters'      => $this->letters,
            'companyLabel' => $this->companyLabel,
        ]);
    }
}
