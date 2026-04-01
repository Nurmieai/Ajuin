<?php

namespace App\Livewire\Student\AcademicService;

use Livewire\Component;
use App\Models\Submission;
use App\Models\SubmissionLetter as SubmissionLetterModel;
use Illuminate\Support\Facades\Auth;

class SubmissionLetterCheck extends Component
{
    public ?Submission $submission = null;
    public ?SubmissionLetterModel $letter = null;

    public function mount(): void
    {
        $this->loadData();
    }

    public function loadData(): void
    {
        // Mencari pengajuan terakhir yang TIDAK dibatalkan terlebih dahulu
        // Jika tidak ada, baru ambil yang paling baru (termasuk yang dibatalkan)
        $this->submission = Submission::where('user_id', Auth::id())
            ->where('status', '!=', 'cancelled')
            ->latest()
            ->first()
            ??
            Submission::where('user_id', Auth::id())->latest()->first();

        if ($this->submission && $this->submission->status === 'approved') {
            $this->letter = SubmissionLetterModel::where('submission_id', $this->submission->id)
                ->latest()
                ->first();
        }
    }

    public function requestLetter(): void
    {
        // Refresh data untuk memastikan status terbaru dari DB
        $this->loadData();

        if (!$this->submission || $this->submission->status !== 'approved') {
            $this->dispatch('toast', message: 'Pengajuan harus disetujui (Diterima) terlebih dahulu.', type: 'error');
            return;
        }

        $existing = SubmissionLetterModel::where('submission_id', $this->submission->id)
            ->whereIn('status', ['requested', 'approved'])
            ->exists();

        if ($existing) {
            $this->dispatch('toast', message: 'Surat sudah dalam proses atau sudah terbit.', type: 'warning');
            return;
        }

        $this->letter = SubmissionLetterModel::create([
            'submission_id' => $this->submission->id,
            'status' => 'requested',
        ]);

        $this->dispatch('toast', message: 'Permintaan surat berhasil dikirim.', type: 'success');
    }

    public function render()
    {
        return view('livewire.student.academic-service.submission-letter-check');
    }
}
