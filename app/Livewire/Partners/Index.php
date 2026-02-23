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

        $partner = Partner::find($this->confirmingId);

        if (!$partner) return;

        $hasActiveSubmission = Submission::where('user_id', $user->id)
            ->whereIn('status', ['submitted', 'approved'])
            ->exists();

        if ($hasActiveSubmission) {
            session()->flash('error', 'Anda sudah memiliki pengajuan aktif!'); // Pakai session
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

        session()->flash('success', 'Pengajuan berhasil dikirim!'); // Pakai session
        $this->confirmingAction = null;
        $this->confirmingId = null;
    }

    public function paginationView()
    {
        return 'components.ui.pagination'; // Arahkan ke file component kamu
    }

    public function render()
    {
        return view('livewire.Partners.index', [
            'partners' => Partner::query()
                ->when($this->search, function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhere('criteria', 'like', '%' . $this->search . '%');
                })
                ->latest()
                ->paginate(10) // Pastikan sudah diganti dari get() ke paginate()
        ]);
    }
}
