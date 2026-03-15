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
use Livewire\Attributes\Validate; // Tambahkan ini

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

    // Ganti dengan Validate attribute untuk real-time validation
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

    // Method untuk reset error saat file diupload ulang
    public function updatedIndustrialVisit()
    {
        $this->resetValidation('industrial_visit');
    }

    public function updatedCompetencyTest()
    {
        $this->resetValidation('competency_test');
    }

    public function updatedSppCard()
    {
        $this->resetValidation('spp_card');
    }

    // Atau gunakan updated() hook generik
    public function updated($propertyName)
    {
        // Reset validation untuk property yang diupdate
        $this->resetValidation($propertyName);

        // Validasi real-time untuk file
        if (in_array($propertyName, ['industrial_visit', 'competency_test', 'spp_card'])) {
            $this->validateOnly($propertyName);
        }
    }

    public function confirmDelete($id)
    {
        $this->confirmingAction = 'delete';
        $this->idBeingDeleted = $id;
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

        $this->confirmingAction = null;
        $this->idBeingDeleted = null;
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
        $this->confirmingId = $id;
        $this->showCertificateModal = true;
        // Reset file dan error saat modal dibuka
        $this->reset(['industrial_visit', 'competency_test', 'spp_card']);
        $this->resetValidation();
    }

    public function submitApplication()
    {
        if ($this->isSubmitting) return;

        $this->isSubmitting = true;

        // Validasi semua file
        $this->validate();

        try {
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

            session()->flash('success', 'Pengajuan berhasil dikirim!');

            $this->reset([
                'industrial_visit',
                'competency_test',
                'spp_card',
                'showCertificateModal',
                'confirmingId'
            ]);
            $this->resetValidation();
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
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
