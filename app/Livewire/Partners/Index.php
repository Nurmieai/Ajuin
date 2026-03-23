<?php

namespace App\Livewire\Partners;

use App\Models\User;
use App\Models\Partner;
use Livewire\Component;
use App\Models\Submission;
use App\Models\Certificates;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;

class Index extends Component
{
    use WithPagination, WithFileUploads;

    #[Url(history: true)]
    public $search = '';

    // State untuk Detail Mitra
    public $selectedPartner = null;

    // State untuk Form Pengajuan (Sertifikat)
    public $confirmingId = null;
    public $isSubmitting = false;

    // State untuk Konfirmasi Hapus (Teacher Only)
    public $idBeingDeleted = null;
    public $confirmingAction = null;

    // Properti Upload dengan Validasi Langsung
    #[Validate('required|file|mimes:pdf,jpg,png|max:2048', message: [
        'required' => 'Sertifikat Industrial Visit wajib diupload.',
        'mimes' => 'Format harus PDF, JPG, atau PNG.',
        'max' => 'Ukuran file maksimal 2MB.'
    ])]
    public $industrial_visit;

    #[Validate('required|file|mimes:pdf,jpg,png|max:2048', message: [
        'required' => 'Sertifikat Competency Test wajib diupload.'
    ])]
    public $competency_test;

    #[Validate('required|file|mimes:pdf,jpg,png|max:2048', message: [
        'required' => 'Kartu SPP wajib diupload.'
    ])]
    public $spp_card;

    /**
     * Listener untuk menangani event dari komponen lain atau script
     */
    protected $listeners = [
        'confirmDelete' => 'confirmDelete'
    ];

    /**
     * Reset pagination saat pencarian berubah
     */
    public function updatedSearch()
    {
        $this->resetPage();
    }

    /**
     * Validasi real-time saat file dipilih
     */
    public function updated($propertyName)
    {
        if (in_array($propertyName, ['industrial_visit', 'competency_test', 'spp_card'])) {
            $this->validateOnly($propertyName);
        }
    }

    // =========================================================
    // LOGIC DETAIL MITRA
    // =========================================================

    public function showDetail($id)
    {
        $this->selectedPartner = Partner::with('majors')->findOrFail($id);
        $this->dispatch('open-detail-modal');
    }

    public function closeDetail()
    {
        $this->selectedPartner = null;
        $this->dispatch('close-detail-modal'); // Opsional jika ingin ditutup via JS
    }

    // =========================================================
    // LOGIC PENGAPLIKASIAN (APPLY) & UPLOAD
    // =========================================================

    public function applyToPartner($id)
    {
        $user = auth()->user();
        $partner = Partner::with('majors')->findOrFail($id);

        // 1. Validasi Jurusan Siswa
        if (!$user->major_id) {
            $this->dispatch('toast', message: 'Profil Anda belum memiliki data jurusan.', type: 'error');
            return;
        }

        // 2. Validasi Kesesuaian Jurusan dengan Mitra
        if ($partner->majors->isNotEmpty()) {
            $allowedMajors = $partner->majors->pluck('id')->toArray();
            if (!in_array($user->major_id, $allowedMajors)) {
                $this->dispatch('toast', message: 'Maaf, jurusan Anda tidak sesuai dengan kebutuhan mitra ini.', type: 'error');
                return;
            }
        }

        // 3. Persiapkan Modal Upload
        $this->confirmingId = $id;
        $this->resetFilesAndModal();
        $this->dispatch('open-certificate-modal');
    }

    public function cancelApply()
    {
        $this->resetFilesAndModal();
        $this->confirmingId = null;
        $this->dispatch('close-certificate-modal');
    }

    private function resetFilesAndModal()
    {
        $this->reset(['industrial_visit', 'competency_test', 'spp_card']);
        $this->resetValidation();
        // Memaksa input file di browser untuk kosong kembali
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

                // Buat data Submission (Header)
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

                // Simpan Berkas Sertifikat
                $files = [
                    ['file' => $this->industrial_visit, 'type' => 'industrial_visit'],
                    ['file' => $this->competency_test, 'type' => 'competency_test'],
                    ['file' => $this->spp_card, 'type' => 'spp_card'],
                ];

                foreach ($files as $item) {
                    $path = $item['file']->store("certificates/submission_{$submission->id}", 'public');

                    Certificates::create([
                        'submission_id' => $submission->id,
                        'file_path' => $path,
                        'type' => $item['type']
                    ]);
                }
            });

            $this->dispatch('toast', message: 'Pengajuan PKL berhasil dikirim!', type: 'success');
            $this->dispatch('close-certificate-modal');
            $this->reset(['confirmingId']);
            $this->resetFilesAndModal();
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('toast', message: 'Harap lengkapi semua berkas yang diminta.', type: 'error');
        } catch (\Exception $e) {
            $this->dispatch('toast', message: 'Gagal mengirim pengajuan: ' . $e->getMessage(), type: 'error');
        }

        $this->isSubmitting = false;
    }

    // =========================================================
    // LOGIC HAPUS MITRA (TEACHER ROLE)
    // =========================================================

    public function confirmDelete($id)
    {
        $this->idBeingDeleted = $id;
        $this->confirmingAction = 'delete';
        // Memicu modal konfirmasi (x-ui.confirmation)
    }

    public function cancelConfirmation()
    {
        $this->reset(['idBeingDeleted', 'confirmingAction']);
    }

    public function deleteConfirmed()
    {
        if (auth()->user()->hasRole('teacher') && $this->idBeingDeleted) {
            $partner = Partner::find($this->idBeingDeleted);
            if ($partner) {
                $partner->delete();
                $this->dispatch('toast', message: 'Mitra berhasil dihapus dari sistem.', type: 'success');
            }
        }
        $this->cancelConfirmation();
    }

    // =========================================================
    // RENDER
    // =========================================================

    public function paginationView()
    {
        return 'components.ui.pagination';
    }

    public function render()
    {
        $query = Partner::query()
            ->with('majors')
            ->when($this->search, function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            });

        return view('livewire.Partners.index', [
            'partners' => $query->latest()->paginate(10)
        ]);
    }
}
