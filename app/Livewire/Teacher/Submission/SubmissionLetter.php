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
        $this->dispatch('open-approve-modal');
    }

    public function approve(): void
    {
        if (!$this->selectedLetter) return;

        $this->selectedLetter->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        Submission::where('id', $this->selectedLetter->submission_id)
            ->update([
                'status' => 'approved',
                'approved_at' => now(),
            ]);

        $this->dispatch('close-approve-modal');
        session()->flash('success', 'Surat ' . $this->selectedSubmission->user->fullname . ' berhasil diterima.');
        $this->selectedLetter = null;
        $this->selectedSubmission = null;
    }

    public function confirmReject($letterId): void
    {
        $this->selectedLetter = SubmissionLetterModel::with('submission.user')->find($letterId);
        $this->selectedSubmission = $this->selectedLetter?->submission;
        $this->dispatch('open-reject-modal');
    }

    public function reject(): void
    {
        if (!$this->selectedLetter) return;

        $this->selectedLetter->update(['status' => 'rejected']);

        Submission::where('id', $this->selectedLetter->submission_id)
            ->update([
                'status' => 'submitted',
                'approved_at' => null,
            ]);

        $this->dispatch('close-reject-modal');
        session()->flash('error', 'Surat ' . $this->selectedSubmission->user->fullname . ' telah ditolak.');
        $this->selectedLetter = null;
        $this->selectedSubmission = null;
    }

    public function downloadLetter($submissionId): void
    {
        $submission = Submission::find($submissionId);

        if (!$submission || $submission->status !== 'approved') {
            session()->flash('error', 'Surat hanya bisa diunduh setelah pengajuan diterima.');
            return;
        }

        redirect()->route('teacher.submission-letter-download', $submissionId);
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

        // Tandai latest letter per submission
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
