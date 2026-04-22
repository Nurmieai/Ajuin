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

    public $selectedSubmission    = null;
    public $selectedLetter        = null;
    public $selectedGroupKey      = null; // key group yang sedang dibuka di modal

    public bool $isStudentModalOpen = false;
    public $selectedCompanyLabel    = null; // label nama perusahaan untuk header modal
    public $selectedCompanyLetters  = [];

    public bool $isApproveOpen = false;
    public bool $isRejectOpen  = false;

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function paginationView(): string
    {
        return 'components.ui.pagination';
    }

    // ===================== HELPER: GROUP KEY =====================

    /**
     * Buat kunci unik per grup:
     * - Mitra  : "partner_{partner_id}"
     * - Mandiri: "manual_{company_name}_{start_date}_{finish_date}"
     */
    private function groupKey(Submission $submission): string
    {
        if ($submission->partner_id) {
            return 'partner_' . $submission->partner_id;
        }

        return 'manual_'
            . $submission->company_name . '_'
            . $submission->start_date->format('Ymd') . '_'
            . $submission->finish_date->format('Ymd');
    }

    // ===================== MODAL SISWA =====================

    public function openStudentModal(string $groupKey): void
    {
        $this->selectedGroupKey = $groupKey;

        $latestLetterIds = SubmissionLetterModel::selectRaw('MAX(id) as id')
            ->groupBy('submission_id')
            ->pluck('id')
            ->toArray();

        $allLetters = SubmissionLetterModel::with(['submission.user'])
            ->whereIn('id', $latestLetterIds)
            ->oldest()
            ->get();

        $filtered = $allLetters->filter(
            fn($l) => $this->groupKey($l->submission) === $groupKey
        )->values();

        $this->selectedCompanyLetters = $filtered->toArray();
        $this->selectedCompanyLabel   = $filtered->first()?->submission?->company_name;
        $this->isStudentModalOpen     = true;

        $this->dispatch('open-student-modal');
    }

    public function closeStudentModal(): void
    {
        $this->isStudentModalOpen     = false;
        $this->selectedGroupKey       = null;
        $this->selectedCompanyLabel   = null;
        $this->selectedCompanyLetters = [];
        $this->dispatch('close-student-modal');
    }

    // ===================== APPROVE / REJECT =====================

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

        $name     = $this->selectedSubmission?->user?->fullname;
        $groupKey = $this->selectedGroupKey;
        $this->cancelConfirmation();

        if ($groupKey) {
            $this->openStudentModal($groupKey);
        }

        $this->dispatch('toast', message: 'Surat ' . $name . ' berhasil diterima.', type: 'success');
    }

    public function confirmReject($letterId): void
    {
        $this->selectedLetter     = SubmissionLetterModel::with('submission.user')->find($letterId);
        $this->selectedSubmission = $this->selectedLetter?->submission;
        $this->isRejectOpen       = true;
    }

    public function reject(): void
    {
        if (!$this->selectedLetter) return;

        $this->selectedLetter->update(['status' => 'rejected']);

        $name     = $this->selectedSubmission?->user?->fullname;
        $groupKey = $this->selectedGroupKey;
        $this->cancelConfirmation();

        if ($groupKey) {
            $this->openStudentModal($groupKey);
        }

        $this->dispatch('toast', message: 'Surat ' . $name . ' telah ditolak.', type: 'error');
    }

    public function cancelConfirmation(): void
    {
        $this->isApproveOpen      = false;
        $this->isRejectOpen       = false;
        $this->selectedLetter     = null;
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

    // ===================== RENDER =====================

    public function render()
    {
        $latestLetterIds = SubmissionLetterModel::selectRaw('MAX(id) as id')
            ->groupBy('submission_id')
            ->pluck('id')
            ->toArray();

        $letters = SubmissionLetterModel::with(['submission.user'])
            ->whereIn('id', $latestLetterIds)
            ->when($this->search, function ($query) {
                $query->whereHas('submission', function ($q) {
                    $q->where('company_name', 'like', '%' . $this->search . '%')
                        ->orWhereHas('user', function ($u) {
                            $u->where('fullname', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->oldest()
            ->get();

        // Group berdasarkan partner_id (mitra) atau company+periode (mandiri)
        $grouped = $letters->groupBy(fn($l) => $this->groupKey($l->submission));

        $page    = $this->getPage();
        $perPage = 10;
        $total   = $grouped->count();

        $groupedPaginated = $grouped->slice(($page - 1) * $perPage, $perPage);

        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $groupedPaginated,
            $total,
            $perPage,
            $page,
            ['path' => request()->url()]
        );

        return view('livewire.teacher.submission.submission-letter', [
            'grouped'   => $groupedPaginated,
            'paginator' => $paginator,
        ]);
    }
}
