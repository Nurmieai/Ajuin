<?php

namespace App\Livewire\Partners;

use Livewire\Component;
use App\Models\Partner;
use App\Models\Submission;
use App\Models\Certificates;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Livewire\Attributes\Url;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;
use Livewire\Attributes\On;

class Index extends Component
{
    use WithPagination, WithFileUploads;

    #[Url(history: true)]
    public $search = '';
    public $selectedPartner = null;
    public $confirmingPartnerId = null;
    public $confirmingAction = null;
    public $confirmingId = null;
    public $idBeingDeleted = null;

    #[Validate('required|file|mimes:pdf,jpg,png|max:2048')]
    public $industrial_visit;

    #[Validate('required|file|mimes:pdf,jpg,png|max:2048')]
    public $competency_test;

    #[Validate('required|file|mimes:pdf,jpg,png|max:2048')]
    public $spp_card;

    public $showCertificateModal = false;
    public $isSubmitting = false;

    protected $listeners = [
        'close-partner-detail' => 'closeDetail',
        'confirmDelete' => 'confirmDelete'
    ];

    public function updated($propertyName)
    {
        $this->resetValidation($propertyName);
        if (in_array($propertyName, ['industrial_visit', 'competency_test', 'spp_card'])) {
            $this->validateOnly($propertyName);
        }
    }

    public function confirmDelete($id)
    {
        $this->confirmingAction = 'delete';
        $this->idBeingDeleted = $id;
    }

    /**
     * Menangani pembatalan dari modal konfirmasi (Hapus/Ajukan)
     */
    public function cancelConfirmation()
    {
        $this->confirmingAction = null;
        $this->idBeingDeleted = null;
        $this->confirmingId = null;
    }

    public function deleteConfirmed()
    {
        $user = auth()->user();

        if ($user && $user->hasRole('teacher') && $this->idBeingDeleted) {
            $partner = Partner::find($this->idBeingDeleted);

            if ($partner) {
                $partner->delete();
                $this->dispatch('toast', message: 'Mitra berhasil dihapus.', type: 'success');
            }
        }

        $this->cancelConfirmation();
    }

    public function updatedSearch()
    {
        if (method_exists($this, 'resetPage')) {
            $this->resetPage();
        }
    }

    public function showDetail($id)
    {
        $this->selectedPartner = Partner::findOrFail($id);
    }

    #[On('close-modal')]
    public function closeDetail()
    {
        $this->selectedPartner = null;
    }

    public function close()
    {
        $this->dispatch('close-partner-detail');
    }

    public function applyToPartner($id)
    {
        $user = auth()->user();
        $partner = Partner::with('majors')->findOrFail($id);

        $userMajorId = $user->major_id;

        if (!$userMajorId) {
            $this->dispatch('toast', message: 'Profil Anda belum memiliki data jurusan.', type: 'error');
            return;
        }

        if ($partner->majors->isNotEmpty()) {
            $allowedMajors = $partner->majors->pluck('id')->toArray();

            if (!in_array($userMajorId, $allowedMajors)) {
                $this->dispatch('toast', message: 'Maaf, jurusan Anda tidak sesuai dengan kebutuhan mitra ini.', type: 'error');
                return;
            }
        }

        $this->confirmingId = $id;
        $this->showCertificateModal = true;
        $this->resetFilesAndModal();
    }

    public function cancelApply()
    {
        $this->showCertificateModal = false;
        $this->resetFilesAndModal();
    }

    private function resetFilesAndModal()
    {
        $this->reset(['industrial_visit', 'competency_test', 'spp_card']);
        $this->resetValidation();
        $this->dispatch('reset-file-inputs');
    }

    public function submitApplication()
    {
        if ($this->isSubmitting) return;
        $this->isSubmitting = true;

        try {
            $this->validate();

            DB::transaction(function () {
                $user = auth()->user();
                $partner = Partner::findOrFail($this->confirmingId);

                $submission = Submission::create([
                    'user_id' => $user->id,
                    'partner_id' => $partner->id,
                    'submission_type' => 'mitra',
                    'status' => 'submitted',
                    'company_name' => $partner->name,
                    'company_email' => $partner->email,
                    'company_phone_number' => $partner->phone_number,
                    'company_address' => $partner->address,
                    'criteria' => $partner->criteria,
                    'start_date' => $partner->start_date,
                    'finish_date' => $partner->finish_date,
                ]);

                $certificates = [
                    ['file' => $this->industrial_visit, 'type' => 'industrial_visit'],
                    ['file' => $this->competency_test, 'type' => 'competency_test'],
                    ['file' => $this->spp_card, 'type' => 'spp_card'],
                ];

                foreach ($certificates as $certificate) {
                    $filePath = $certificate['file']->store(
                        'certificates/submission_' . $submission->id,
                        'public'
                    );

                    Certificates::create([
                        'submission_id' => $submission->id,
                        'file_path' => $filePath,
                        'type' => $certificate['type']
                    ]);
                }
            });

            $this->dispatch('toast', message: 'Pengajuan berhasil dikirim!', type: 'success');

            $this->showCertificateModal = false;
            $this->reset(['confirmingId']);
            $this->resetFilesAndModal();
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('toast', message: 'Harap periksa kembali berkas Anda.', type: 'error');
        } catch (\Exception $e) {
            $this->dispatch('toast', message: 'Terjadi kesalahan sistem: ' . $e->getMessage(), type: 'error');
            $this->resetFilesAndModal();
        }

        $this->isSubmitting = false;
    }

    public function paginationView()
    {
        return 'components.ui.pagination';
    }

    public function render()
    {
        $user = auth()->user();
        $query = Partner::query();

        $query->when($this->search, function ($query) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        });

        return view('livewire.Partners.index', [
            'partners' => $query->latest()->paginate(10)
        ]);
    }
}
