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
            'finish_date' => 'required|date|after_or_equal:start_date',
            'selectedMajors' => 'required|array'
        ], [
            'name.required' => 'Nama mitra wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'phone_number.required' => 'Nomor telepon wajib diisi.',
            'address.required' => 'Alamat wajib diisi.',
            'criteria.required' => 'Kriteria wajib diisi.',
            'quota.required' => 'Kuota wajib diisi.',
            'quota.integer' => 'Kuota harus berupa angka.',
            'start_date.required' => 'Tanggal mulai wajib diisi.',
            'start_date.date' => 'Format tanggal mulai tidak valid.',
            'finish_date.required' => 'Tanggal berakhir wajib diisi.',
            'finish_date.date' => 'Format tanggal berakhir tidak valid.',
            'finish_date.after_or_equal' => 'Tanggal berakhir tidak boleh sebelum tanggal mulai.',
            'selectedMajors.required' => 'Minimal pilih satu jurusan.',
            'selectedMajors.array' => 'Format jurusan tidak valid.',
        ]);

        try {
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

            session()->flash('success', 'Data mitra berhasil disimpan.');
            $this->redirectRoute('partners.index', navigate: true);
        } catch (\Exception $e) {
            // Toast komponen Anda di tampilan akan mendeteksi session('error') ini
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.Partners.form');
    }
}
