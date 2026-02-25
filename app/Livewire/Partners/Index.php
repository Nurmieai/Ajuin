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

    public $industrial_visit;
    public $competency_test;
    public $spp_card;

    public $showCertificateModal = false;
    public $isSubmitting = false;

    protected $listeners = [
        'close-partner-detail' => 'closeDetail',
        'confirmDelete' => 'confirmDelete'
    ];
    public function confirmDelete($id)
    {
        $this->confirmingAction = 'delete'; // Membuka modal konfirmasi
        $this->idBeingDeleted = $id;        // Menyimpan ID sementara
    }

    public function deleteConfirmed()
    {
        $user = auth()->user();

        if ($user && $user->hasRole('teacher') && $this->idBeingDeleted) {
            $partner = Partner::find($this->idBeingDeleted);

            if ($partner) {
                $partner->delete();

                // Kirim toast
                $this->dispatch(
                    'toast',
                    message: 'Mitra berhasil dihapus.',
                    type: 'success'
                );
            }
        }

        // Reset state modal
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
    }

    public function submitApplication()
    {
        if ($this->isSubmitting) return; // cegah double click

        $this->isSubmitting = true;

        $this->validate([
            'industrial_visit' => 'required|file|mimes:pdf,jpg,png|max:2048',
            'competency_test' => 'required|file|mimes:pdf,jpg,png|max:2048',
            'spp_card' => 'required|file|mimes:pdf,jpg,png|max:2048',
        ]);

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
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan.');
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

        // Jika user punya jurusan, filter mitra yang terhubung dengan jurusan tersebut
        if ($user->major_id) {
            $query->whereHas('majors', function ($q) use ($user) {
                $q->where('majors.id', $user->major_id);
            });
        }

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
