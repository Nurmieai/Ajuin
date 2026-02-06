<?php

namespace App\Livewire\Partners;

use Livewire\Component;
use App\Models\Partner;

class Detail extends Component
{
    public Partner $partner;

    public function mount(Partner $partner)
    {
        $this->partner = $partner;
    }

    public function close()
    {
        $this->dispatch('close-partner-detail');
    }

    public function render()
    {
        return view('livewire.partners.detail');
    }
}
