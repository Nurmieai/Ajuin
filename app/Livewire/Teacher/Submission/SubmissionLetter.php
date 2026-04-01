<?php

namespace App\Livewire\Teacher\Submission;

use App\Models\Submission;
use App\Models\SubmissionLetter as SubmissionLetterModel;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class SubmissionLetter extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public $search = '';

    public $selectedSubmission = null;
    public $selectedLetter = null;

    public bool $isApproveOpen = false;
    public bool $isRejectOpen = false;

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function paginationView(): string
    {
        return 'components.ui.pagination';
    }

    public function confirmApprove($letterId): void
    {
        $this->selectedLetter = SubmissionLetterModel::with('submission.user')->find($letterId);
        $this->selectedSubmission = $this->selectedLetter?->submission;
        $this->isApproveOpen = true;
    }

    public function approve(): void
    {
        if (!$this->selectedLetter) return;

        $this->selectedLetter->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        // Pastikan submission tetap approved
        Submission::where('id', $this->selectedLetter->submission_id)
            ->update([
                'status' => 'approved',
                'approved_at' => now(),
            ]);

        $this->cancelConfirmation();
        $this->dispatch('toast', message: 'Surat ' . $this->selectedSubmission?->user?->fullname . ' berhasil diterima.', type: 'success');
    }

    public function confirmReject($letterId): void
    {
        $this->selectedLetter = SubmissionLetterModel::with('submission.user')->find($letterId);
        $this->selectedSubmission = $this->selectedLetter?->submission;
        $this->isRejectOpen = true;
    }

    public function reject(): void
    {
        if (!$this->selectedLetter) return;

        // Cukup ubah status surat menjadi rejected
        $this->selectedLetter->update(['status' => 'rejected']);

        /** * JANGAN mengubah status submission menjadi 'submitted' 
         * agar siswa tetap bisa melakukan 'Ajukan Ulang' di halaman mereka.
         */

        $this->cancelConfirmation();
        $this->dispatch('toast', message: 'Surat ' . $this->selectedSubmission?->user?->fullname . ' telah ditolak.', type: 'error');
    }

    public function cancelConfirmation(): void
    {
        $this->isApproveOpen = false;
        $this->isRejectOpen = false;
        $this->selectedLetter = null;
        $this->selectedSubmission = null;
    }

    public function downloadLetter($submissionId): void
    {
        $submission = Submission::find($submissionId);

        if (!$submission || $submission->status !== 'approved') {
            $this->dispatch('toast', message: 'Surat hanya bisa diunduh setelah pengajuan diterima.', type: 'error');
            return;
        }

        $this->redirectRoute('teacher.submission-letter-download', $submissionId);
    }

    public function render()
    {
        $letters = SubmissionLetterModel::with(['submission.user'])
            ->when($this->search, function ($query) {
                $query->whereHas('submission', function ($q) {
                    $q->where('company_name', 'like', '%' . $this->search . '%')
                        ->orWhereHas('user', function ($u) {
                            $u->where('fullname', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->latest()
            ->paginate(10);

        $latestLetterIds = SubmissionLetterModel::selectRaw('MAX(id) as id')
            ->groupBy('submission_id')
            ->pluck('id')
            ->toArray();

        return view('livewire.teacher.submission.submission-letter', [
            'letters' => $letters,
            'latestLetterIds' => $latestLetterIds,
        ]);
    }
}
