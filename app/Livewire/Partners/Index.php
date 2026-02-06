<?php

namespace App\Livewire\Partners;

use Livewire\Component;
use App\Models\Partner;

class Index extends Component
{
    public $partners;
    public $selectedPartner = null;

    protected $listeners = ['close-partner-detail' => 'closeDetail'];

    public function mount()
    {
        $this->partners = Partner::latest()->get();
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
        return view('livewire.partners.index');
    }
}
