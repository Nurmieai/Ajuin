<?php

namespace App\Livewire\Partners;

use Livewire\Component;
use App\Models\Partner;
use App\Models\Major;

class Form extends Component
{
    public $partnerId;
    public $name, $email, $phone_number, $quota, $criteria, $address, $start_date, $finish_date;

    public $selectedMajors = [];
    public $allMajors = [];

    public function mount($partnerId = null)
    {
        $this->allMajors = Major::all();
        $this->partnerId = $partnerId;

        if ($partnerId) {
            $partner = Partner::with('majors')->findOrFail($partnerId);
            $this->fill($partner->toArray());

            $this->selectedMajors = $partner->majors
                ->pluck('id')
                ->toArray();
        }
    }

    public function save()
    {
        $this->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone_number' => 'required',
            'address' => 'required',
            'criteria' => 'required',
            'quota' => 'required|integer',
            'start_date' => 'required|date',
            'finish_date' => 'required|date',
            'selectedMajors' => 'required|array'
        ]);

        $partner = Partner::updateOrCreate(
            ['id' => $this->partnerId],
            $this->only([
                'name',
                'email',
                'phone_number',
                'quota',
                'criteria',
                'address',
                'start_date',
                'finish_date'
            ])
        );

        // 🔥 Sync jurusan
        $partner->majors()->sync($this->selectedMajors);

        return redirect()->route('partners.index');
    }

    public function render()
    {
        return view('livewire.Partners.form');
    }
}
