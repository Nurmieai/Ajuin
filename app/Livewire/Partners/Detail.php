<?php

namespace App\Livewire\Partners;

use Livewire\Component;
use App\Models\Partner;
use Livewire\Attributes\On;

class Detail extends Component
{
    public ?Partner $partner = null;

    #[On('showDetail')]
    public function loadPartner($id)
    {
        // Ambil data baru
        $this->partner = Partner::with('majors')->find($id);

        if ($this->partner) {
            $this->dispatch('open-detail-modal');
        }
    }

    // Fungsi ini dipanggil HANYA setelah animasi tutup selesai
    public function resetData()
    {
        $this->partner = null;
    }

    public function render()
    {
        return view('livewire.Partners.detail');
    }
}
