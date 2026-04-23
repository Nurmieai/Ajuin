<?php

namespace App\Livewire\Teacher\Submission;

use App\Models\Submission;
use App\Models\Partner;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $confirmingAction = null;
    public $selectedSubmission = null;
    public $showDetailModal = false;

    #[Url]
    public $activeTab = 'mandiri';

    #[Url(history: true)]
    public $search = '';

    public function updatedSearch()
    {
        $this->resetPage();
    }

        public function setTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    #[On('submission-updated')]
    public function refreshSubmissions() {}

    public function cancelConfirmation()
    {
        $this->reset('confirmingAction');
    }

    public function confirmApprove($submissionId)
    {
        $this->selectedSubmission = Submission::findOrFail($submissionId);

        $hasApprovedSubmission = Submission::where('user_id', $this->selectedSubmission->user_id)
            ->where('status', 'approved')
            ->exists();

        if ($hasApprovedSubmission) {
            $this->dispatch('toast', type: 'error', message: 'Siswa ini sudah memiliki pengajuan yang diterima');
            if (!$this->showDetailModal) {
                $this->reset('selectedSubmission');
            }
            return;
        }

        $this->confirmingAction = 'approve';
    }

    public function confirmReject($submissionId)
    {
        $this->selectedSubmission = Submission::findOrFail($submissionId);
        $this->confirmingAction = 'reject';
    }

    public function approve()
    {
        if (!$this->selectedSubmission) return;

        try {
            DB::beginTransaction();

            $submission = Submission::lockForUpdate()->find($this->selectedSubmission->id);

            $hasApprovedSubmission = Submission::where('user_id', $submission->user_id)
                ->where('status', 'approved')
                ->lockForUpdate()
                ->exists();

            if ($hasApprovedSubmission) {
                DB::rollBack();
                $this->dispatch('toast', type: 'error', message: 'Siswa sudah memiliki pengajuan yang diterima');
                $this->reset(['selectedSubmission', 'confirmingAction', 'showDetailModal']);
                return;
            }

            // --- LOGIKA PENGURANGAN KUOTA MITRA ---
            if ($submission->submission_type === 'mitra') {
                $partner = $submission->partner;
                if ($partner) {
                    if ($partner->quota <= 0) {
                        DB::rollBack();
                        $this->dispatch('toast', type: 'error', message: 'Kuota mitra ini sudah habis.');
                        $this->reset(['selectedSubmission', 'confirmingAction', 'showDetailModal']);
                        return;
                    }
                    // Kurangi kuota
                    $partner->decrement('quota');
                }
            }
            // --------------------------------------

            $submission->update([
                'status' => 'approved',
                'approved_at' => now()
            ]);

            Submission::where('user_id', $submission->user_id)
                ->where('id', '!=', $submission->id)
                ->whereIn('status', ['submitted', 'rejected'])
                ->update(['status' => 'cancelled']);

            DB::commit();

            $this->reset(['selectedSubmission', 'confirmingAction', 'showDetailModal']);

            $this->dispatch('close-teacher-detail-modal');

            $this->dispatch('toast', type: 'success', message: 'Pengajuan berhasil diterima dan kuota mitra diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            $this->dispatch('toast', type: 'error', message: 'Terjadi kesalahan sistem.');
        }
    }

    public function reject()
    {
        if (!$this->selectedSubmission) return;

        try {
            $this->selectedSubmission->update([
                'status' => 'rejected'
            ]);

            $this->reset(['selectedSubmission', 'confirmingAction', 'showDetailModal']);

            $this->dispatch('close-teacher-detail-modal');

            $this->dispatch('toast', type: 'success', message: 'Pengajuan berhasil ditolak');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            $this->dispatch('toast', type: 'error', message: 'Gagal menolak pengajuan.');
        }
    }

    #[On('showDetail')]
    public function showDetail($id)
    {
        $submissionId = is_array($id) ? ($id['id'] ?? $id[0]) : $id;

        $this->selectedSubmission = Submission::with(['user', 'certificates'])
            ->findOrFail($submissionId);

        $this->dispatch('open-teacher-detail-modal');
    }

    public function closeDetail()
    {
        $this->reset(['selectedSubmission', 'confirmingAction']);
    }

    public function paginationView()
    {
        return 'components.ui.pagination';
    }

    public function render()
    {
        $approvedUserIds = Submission::where('status', 'approved')
            ->pluck('user_id')
            ->toArray();

        $submissions = Submission::with('user')
            ->where('status', 'submitted')
            ->where('submission_type', $this->activeTab)
            ->addselect([
                'has_approved' => Submission::selectRaw('count(*) > 0')
                    ->whereColumn('user_id', 'submissions.user_id')
                    ->where('status','approved')
                    ->limit(1)
            ])
            ->when($this->search, function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->whereHas('user', function ($userQuery) {
                        $userQuery->where('fullname', 'like', '%' . $this->search . '%')
                            ->orWhere('nisn', 'like', '%' . $this->search . '%');
                    })
                        ->orWhere('company_name', 'like', '%' . $this->search . '%')
                        ->orWhere('company_email', 'like', '%' . $this->search . '%')
                        ->orWhere('company_phone_number', 'like', '%' . $this->search . '%');
                });
            })
            ->latest()
            ->paginate(10);

        return view('livewire.teacher.submission.index', [
            'submissions' => $submissions,
            'approvedUserIds' => $approvedUserIds
        ]);
    }
}
