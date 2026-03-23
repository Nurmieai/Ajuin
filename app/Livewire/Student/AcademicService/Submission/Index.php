<?php

namespace App\Livewire\Student\AcademicService\Submission;

use App\Models\Submission;
use FontLib\Table\Type\name;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class Index extends Component
{
    public $search = '';
    public $selectedSubmission = null;

    public function confirmDelete($submissionId)
    {
        // Cari data dan simpan ke properti agar bisa dibaca oleh modal konfirmasi
        $this->selectedSubmission = Submission::where('user_id', auth()->id())
            ->findOrFail($submissionId);

        if (!$this->selectedSubmission->canBeDeleted()) {
            $this->dispatch('error', 'Pengajuan tidak dapat dihapus');
            return;
        }

        $this->dispatch('open-delete-modal');
    }

    public function delete()
    {
        if (!$this->selectedSubmission) {
            return;
        }

        try {
            DB::beginTransaction();


            $submissionId = $this->selectedSubmission->id;

            foreach ($this->selectedSubmission->certificates as $certificate) {
                if (Storage::disk('public')->exists($certificate->file_path)) {
                    Storage::disk('public')->delete($certificate->file_path);
                }
            }

            $folderPath = 'certificates/submission_' . $submissionId;
            if (Storage::disk('public')->exists($folderPath)) {
                Storage::disk('public')->deleteDirectory($folderPath);
            }

            // cascade (jga ada di database)
            $this->selectedSubmission->delete();

            DB::commit();

            $this->reset('selectedSubmission');
            $this->dispatch('close-delete-modal');

            $this->dispatch('success', 'Pengajuan berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            $this->dispatch('error', 'Terjadi kesalahan, silahkan coba lagi');
        }
    }

    public function redirectToEdit($submissionId)
    {
        $submission = Submission::where('user_id', auth()->id())
            ->findOrFail($submissionId);

        if (!$submission->canBeEdited()) {
            session()->flash('error', 'Pengajuan tidak dapat diubah');
            return;
        }

        $this->redirectRoute('student.submission-edit', $submissionId, navigate: true);
    }

    public function render()
    {
        $query = Submission::with('certificates')
            ->where('user_id', auth()->id());

        // Filter pencarian
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('company_name', 'like', '%' . $this->search . '%')
                    ->orWhere('status', 'like', '%' . $this->search . '%')
                    ->orWhereDate('start_date', $this->search)
                    ->orWhereDate('finish_date', $this->search);
            });
        }

        $submissions = $query->latest()->get();

        $hasApprovedSubmission = Submission::where('user_id', auth()->id())
            ->where('status', 'approved') // sesuaikan dengan value di database
            ->exists();

        return view('livewire.student.academic-service.submission.index', [
            'submissions' => $submissions,
            'hasApprovedSubmission' => $hasApprovedSubmission
        ]);
    }
}
