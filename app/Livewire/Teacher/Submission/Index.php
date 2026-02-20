<?php

namespace App\Livewire\Teacher\Submission;

use App\Models\Submission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $selectedSubmission = null;
    public $showDetailModal = false;

    #[Url(history: true)]
    public $search = '';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    #[On('submission-updated')]
    public function refreshSubmissions() {}

    public function confirmApprove($submissionId)
    {
        $this->selectedSubmission = Submission::findOrFail($submissionId);
        // jangan dioprek!!!
        $hasApprovedSubmission = Submission::where('user_id', $this->selectedSubmission->user_id)
            ->where('status', 'approved')
            ->exists();

        if ($hasApprovedSubmission) {
            session()->flash('error', 'Siswa ini sudah memiliki pengajuan yang diterima');
            $this->reset('selectedSubmission');
            return;
        }

        $this->dispatch('open-approve-modal');
    }

    public function confirmReject($submissionId)
    {
        $this->selectedSubmission = Submission::findOrFail($submissionId);
        $this->dispatch('open-reject-modal');
    }

    public function approve()
    {
        if (!$this->selectedSubmission) {
            return;
        }

        try {
            DB::beginTransaction();

            $hasApprovedSubmission = Submission::where('user_id', $this->selectedSubmission->user_id)
                ->where('status', 'approved')
                ->exists();

            if ($hasApprovedSubmission) {
                DB::rollBack();
                session()->flash('error', 'Siswa ini sudah memiliki pengajuan yang diterima');
                $this->reset('selectedSubmission');
                $this->dispatch('close-approve-modal');
                return;
            }
            
            $this->selectedSubmission->update([
                'status' => 'approved',
                'approved_at' => now()
            ]);

        Submission::where('user_id', $this->selectedSubmission->user_id)
            ->where('id', '!=', $this->selectedSubmission->id)
            ->whereIn('status', ['submitted', 'rejected'])
            ->update(['status' => 'cancelled']);

        DB::commit();

            $this->reset('selectedSubmission');
            $this->dispatch('close-approve-modal');

            session()->flash('success', 'Pengajuan berhasil diterima');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            session()->flash('error', 'Terjadi kesalahan saat menyetujui.');
        }
    }

    public function reject()
    {
        if (!$this->selectedSubmission) {
            return;
        }

        try {
            $this->selectedSubmission->update([
                'status' => 'rejected'
            ]);

            $this->reset('selectedSubmission');
            $this->dispatch('close-reject-modal');

            session()->flash('success', 'Pengajuan berhasil ditolak');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            session()->flash('error', 'Terjadi kesalahan saat menolak.');
        }
    }

    public function showDetail($submissionId)
    {
        $this->selectedSubmission = Submission::with(['user', 'certificates'])
            ->findOrFail($submissionId);
        $this->showDetailModal = true;
    }

    public function closeDetail()
    {
        $this->showDetailModal = false;
        $this->reset('selectedSubmission');
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
            ->where('submission_type', 'mandiri')
            ->where('status', 'submitted')
            ->when($this->search, function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->whereHas('user', function ($userQuery) {
                        $userQuery->where('fullname', 'like', '%' . $this->search . '%')
                            ->orWhere('nisn', 'like', '%' . $this->search . '%');
                    })
                        ->orWhere('company_name', 'like', '%' . $this->search . '%')
                        ->orWhere('company_email', 'like', '%' . $this->search . '%')
                        ->orWhere('company_phone_number', 'like', '%' . $this->search . '%')

                        ->orWhere('start_date', 'like', '%' . $this->search . '%')
                        ->orWhere('finish_date', 'like', '%' . $this->search . '%');
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
