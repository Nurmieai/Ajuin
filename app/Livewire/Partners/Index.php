<?php

namespace App\Livewire\Partners;

use Livewire\Component;
use App\Models\Partner;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed; // Opsional untuk performa

class Index extends Component
{
    public $selectedPartner = null;

    protected $listeners = [
        'close-partner-detail' => 'closeDetail',
        'confirmDelete' => 'deletePartner' // Listener untuk event hapus
    ];

    public function deletePartner($id)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        // Pastikan user ada dan punya role teacher
        if ($user && $user->hasRole('teacher')) {
            Partner::findOrFail($id)->delete();
            session()->flash('message', 'Mitra berhasil dihapus.');
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

    public function render()
    {
        return view('livewire.Partners.index', [
            // Mengambil data terbaru langsung di render
            'partners' => Partner::latest()->get()
        ]);
    }
}
