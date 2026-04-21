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
use Carbon\Carbon;

class Index extends Component
{
    use WithPagination, WithFileUploads;

    #[Url(history: true)]
    public $search = '';

    #[Url(history: true)]
    public $sortDirection = 'asc';

    #[Url(history: true)]
    public $startDate = '';

    #[Url(history: true)]
    public $endDate = '';

    public $selectedPartner = null;
    public $confirmingId = null;
    public $isSubmitting = false;
    public $idBeingDeleted = null;
    public $confirmingAction = null;

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

    protected $listeners = [
        'confirmDelete' => 'confirmDelete'
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['industrial_visit', 'competency_test', 'spp_card'])) {
            $this->validateOnly($propertyName);
        }
    }

    public function updatedStartDate()
    {
        $this->resetPage();
    }

    public function updatedEndDate()
    {
        $this->resetPage();
    }

    public function toggleSort()
    {
        $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
    }

    public function resetFilters()
    {
        $this->reset(['search', 'startDate', 'endDate']);
    }

    public function showDetail($id)
    {
        $this->dispatch('showDetail', id: $id);
    }

    public function applyToPartner($id)
    {
        $user = auth()->user();

        /** * Validasi: Cek pengajuan aktif.
         * User tidak boleh mengajukan jika sudah ada pengajuan dengan status selain 'rejected' atau 'cancelled'.
         * Artinya jika statusnya 'submitted', 'reviewed', atau 'approved', mereka tidak bisa apply lagi.
         */
        $activeSubmission = Submission::where('user_id', $user->id)
            ->whereNotIn('status', ['rejected', 'cancelled'])
            ->first();

        if ($activeSubmission) {
            $message = $activeSubmission->status === 'approved'
                ? 'Pengajuan Anda sudah diterima. Tidak dapat melakukan pengajuan baru.'
                : 'Anda masih memiliki pengajuan yang sedang diproses.';

            $this->dispatch('toast', message: $message, type: 'error');
            return;
        }

        $partner = Partner::with('majors')->findOrFail($id);

        if ($partner->finish_date && Carbon::parse($partner->finish_date)->isPast()) {
            $this->dispatch('toast', message: 'Maaf, periode pendaftaran untuk mitra ini telah berakhir.', type: 'error');
            return;
        }

        if (!$user->major_id) {
            $this->dispatch('toast', message: 'Profil Anda belum memiliki data jurusan.', type: 'error');
            return;
        }

        if ($partner->majors->isNotEmpty()) {
            $allowedMajors = $partner->majors->pluck('id')->toArray();
            if (!in_array($user->major_id, $allowedMajors)) {
                $this->dispatch('toast', message: 'Maaf, jurusan Anda tidak sesuai dengan kebutuhan mitra ini.', type: 'error');
                return;
            }
        }

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

                // Double check untuk keamanan concurency
                $activeSubmission = Submission::where('user_id', $user->id)
                    ->whereNotIn('status', ['rejected', 'cancelled'])
                    ->first();

                if ($activeSubmission) {
                    throw new \Exception('Anda masih memiliki pengajuan aktif.');
                }

                $partner = Partner::findOrFail($this->confirmingId);

                if ($partner->finish_date && Carbon::parse($partner->finish_date)->isPast()) {
                    throw new \Exception('Periode pendaftaran sudah berakhir.');
                }

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

    public function confirmDelete($id)
    {
        $this->idBeingDeleted = $id;
        $this->confirmingAction = 'delete';
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

    public function paginationView()
    {
        return 'components.ui.pagination';
    }

    public function render()
    {
        $cleanSearch = str_replace(',', '.', $this->search);

        $query = Partner::query()
            ->with('majors')
            ->withAvg('reviews', 'rating')
            ->when($this->search, function ($q) use ($cleanSearch) {
                $searchTerm = '%' . $this->search . '%';

                $q->where(function ($sub) use ($searchTerm, $cleanSearch) {
                    $sub->where('name', 'like', $searchTerm)
                        ->orWhere('email', 'like', $searchTerm)
                        ->orWhere('criteria', 'like', $searchTerm)
                        ->orWhereHas('majors', function ($majorQuery) use ($searchTerm) {
                            $majorQuery->where('name', 'like', $searchTerm)
                                ->orWhere('abbreviation', 'like', $searchTerm);
                        });

                    if (ctype_digit($this->search)) {
                        $sub->orWhere('quota', '=', $this->search);
                    }

                    if (is_numeric($cleanSearch)) {
                        $sub->orWhereIn('id', function ($query) use ($cleanSearch) {
                            $query->select('partner_id')
                                ->from('reviews')
                                ->groupBy('partner_id')
                                ->havingRaw('ROUND(AVG(rating), 1) = ?', [$cleanSearch]);
                        });
                    }
                });
            })
            ->when($this->startDate, function ($q) {
                $q->whereDate('start_date', '>=', $this->startDate);
            })
            ->when($this->endDate, function ($q) {
                $q->whereDate('finish_date', '<=', $this->endDate);
            })
            ->orderBy('start_date', $this->sortDirection);

        return view('livewire.Partners.index', [
            'partners' => $query->latest()->paginate(10)
        ]);
    }
}
