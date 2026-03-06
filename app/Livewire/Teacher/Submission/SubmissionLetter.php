<?php

namespace App\Livewire\Teacher\Submission;

use App\Models\Submission;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class SubmissionLetter extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public $search = '';

    public $selectedSubmission;

    // Reset pagination ke halaman 1 saat mulai mencari
    public function updatedSearch()
    {
        $this->resetPage();
    }

    // Menggunakan view pagination custom
    public function paginationView()
    {
        return 'components.ui.pagination';
    }

    public function showDetail($id)
    {
        return $this->redirectRoute(
            'teacher.teacher-submission-letter-detail',
            ['id' => $id],
            navigate: true
        );
    }

    public function confirmApprove($id)
    {
        $this->selectedSubmission = Submission::with('user')->find($id);
        $this->dispatch('open-approve-modal');
    }

    public function approve()
    {
        if (!$this->selectedSubmission) return;

        $this->selectedSubmission->update(['status' => 'approved']);

        $this->dispatch('close-approve-modal');

        // Menggunakan Session Flash untuk Toast
        session()->flash('success', 'Pengajuan ' . $this->selectedSubmission->user->fullname . ' berhasil diterima.');

        $this->selectedSubmission = null;
    }

    public function confirmReject($id)
    {
        $this->selectedSubmission = Submission::with('user')->find($id);
        $this->dispatch('open-reject-modal');
    }

    public function reject()
    {
        if (!$this->selectedSubmission) return;

        $this->selectedSubmission->update(['status' => 'rejected']);

        $this->dispatch('close-reject-modal');

        // Menggunakan Session Flash untuk Toast
        session()->flash('error', 'Pengajuan ' . $this->selectedSubmission->user->fullname . ' telah ditolak.');

        $this->selectedSubmission = null;
    }

    public function render()
    {
        $submissions = Submission::with('user')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('company_name', 'like', '%' . $this->search . '%')
                        ->orWhereHas('user', function ($u) {
                            $u->where('fullname', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->latest()
            ->paginate(10);

        return view('livewire.teacher.submission.submission-letter', [
            'submissions' => $submissions
        ]);
    }
}
