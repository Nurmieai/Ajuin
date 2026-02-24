<?php

namespace App\Livewire\Partners;

use Livewire\Component;
use App\Models\Partner;
use App\Models\Submission;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use App\Models\User;

class Index extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public $search = '';
    public $selectedPartner = null;
    public $confirmingPartnerId = null;
    public $confirmingAction = null;
    public $confirmingId = null;
    public $idBeingDeleted = null;

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
        $this->confirmingAction = 'apply';
        $this->confirmingId = $id;
    }

    public function confirmApply()
    {
        if (!$this->confirmingId) return;

        $user = auth()->user();

        // Perubahan di sini: Menggunakan whereHas karena relasi Many-to-Many
        $partner = Partner::where('id', $this->confirmingId)
            ->whereHas('majors', function ($query) use ($user) {
                $query->where('majors.id', $user->major_id);
            })
            ->first();

        // Validasi jika partner tidak ditemukan atau tidak sesuai jurusan
        if (!$partner) {
            session()->flash('error', 'Mitra tidak ditemukan atau tidak sesuai dengan jurusan Anda!');
            $this->confirmingAction = null;
            $this->confirmingId = null; // Tambahkan ini agar state reset
            return;
        }

        $hasActiveSubmission = Submission::where('user_id', $user->id)
            ->whereIn('status', ['submitted', 'approved'])
            ->exists();

        if ($hasActiveSubmission) {
            session()->flash('error', 'Anda sudah memiliki pengajuan aktif!');
            $this->confirmingAction = null;
            $this->confirmingId = null;
            return;
        }

        Submission::create([
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

        session()->flash('success', 'Pengajuan berhasil dikirim!');
        $this->confirmingAction = null;
        $this->confirmingId = null;
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
