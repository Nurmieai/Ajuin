<?php

namespace App\Livewire\Partners;

use Livewire\Component;
use App\Models\Partner;

class Form extends Component
{
    public $partnerId;
    public $name, $quota, $criteria, $address, $start_date, $finish_date;

    public function mount($partnerId = null)
    {
        if ($partnerId) {
            $partner = Partner::findOrFail($partnerId);
            $this->fill($partner->toArray());
        }
    }

    public function save()
    {
        $this->validate([
            'name' => 'required',
            'quota' => 'required|integer',
            'start_date' => 'required|date',
            'finish_date' => 'required|date',
        ]);

        Partner::updateOrCreate(
            ['id' => $this->partnerId],
            $this->only([
                'name',
                'quota',
                'criteria',
                'address',
                'start_date',
                'finish_date'
            ])
        );

        return redirect()->route('partners.index');
    }

    public function render()
    {
        return view('livewire.Partners.form');
    }
}
