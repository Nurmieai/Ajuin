<?php

namespace App\Livewire\Partners;

use Livewire\Component;
use App\Models\Partner;
use Livewire\WithPagination;
use Livewire\Attributes\Url; // Tambahkan ini agar pencarian masuk ke URL

class Index extends Component
{
    use WithPagination;

    // Tambahkan atribut Url agar saat refresh hasil pencarian tidak hilang
    #[Url(history: true)]
    public $search = '';

    public $selectedPartner = null;

    protected $listeners = [
        'close-partner-detail' => 'closeDetail',
        'confirmDelete' => 'confirmDelete' // Pastikan nama method sama
    ];
    public function confirmDelete($id)
    {
        $user = auth()->user();

        if ($user && $user->hasRole('teacher')) {
            Partner::findOrFail($id)->delete();
            session()->flash('message', 'Mitra berhasil dihapus.');
        }
    }

    // Reset halaman ke nomor 1 setiap kali mengetik pencarian baru
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

    public function render()
    {
        return view('livewire.Partners.index', [
            // Filter data berdasarkan nama, email, atau kriteria
            'partners' => Partner::query()
                ->when($this->search, function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhere('criteria', 'like', '%' . $this->search . '%');
                })
                ->latest()
                ->get() // Atau gunakan ->paginate(10) jika data sudah banyak
        ]);
    }
}
