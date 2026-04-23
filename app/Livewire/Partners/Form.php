<?php

namespace App\Livewire\Partners;

use Livewire\Component;
use App\Models\Partner;
use App\Models\Major;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class Form extends Component
{
    public $partnerId;
    public $name, $email, $phone_number, $quota, $address, $start_date, $finish_date;

    public $criteriaList = [];
    public $selectedMajors = [];

    public function mount($partnerId = null)
    {
        $this->partnerId = $partnerId;

        if ($partnerId) {
            $partner = Partner::with('majors')->findOrFail($partnerId);
            $this->name = $partner->name;
            $this->email = $partner->email;
            $this->phone_number = $partner->phone_number;
            $this->quota = $partner->quota;
            $this->address = $partner->address;
            $this->start_date = \Carbon\Carbon::parse($partner->start_date)->format('Y-m-d');
            $this->finish_date = \Carbon\Carbon::parse($partner->finish_date)->format('Y-m-d');
            $this->selectedMajors = $partner->majors->pluck('id')->map(fn($id) => (string)$id)->toArray();
            if ($partner->criteria) {
                $this->criteriaList = array_map('trim', explode(',', $partner->criteria));
            }
        }
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('partners', 'email')->ignore($this->partnerId)
            ],
            'phone_number' => 'required|phone:ID,MOBILE',
            'address' => 'required|string|max:255',
            'criteriaList' => 'required|array|min:1|max:10',
            'criteriaList.*' => 'string|max:30',
            'quota' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'finish_date' => 'required|date|after_or_equal:start_date',
            'selectedMajors' => 'required|array|min:1'
        ], [
            'name.required' => 'Nama mitra wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah terdaftar.',
            'phone_number.phone' => 'Gunakan format telepon Indonesia yang valid (08xxx atau +628xxx).',
            'phone_number.required' => 'Nomor telepon wajib diisi.',
            'address.required' => 'Alamat wajib diisi.',
            'criteriaList.required' => 'Minimal tambahkan satu kriteria.',
            'criteriaList.min' => 'Minimal tambahkan satu kriteria.',
            'criteriaList.max' => 'Maksimal kriteria yang diizinkan adalah 10 item.',
            'criteriaList.*.max' => 'Setiap kriteria maksimal berisi 30 karakter.',
            'quota.required' => 'Kuota wajib diisi.',
            'quota.integer' => 'Kuota harus berupa angka.',
            'start_date.required' => 'Tanggal mulai wajib diisi.',
            'start_date.date' => 'Format tanggal mulai tidak valid.',
            'finish_date.required' => 'Tanggal berakhir wajib diisi.',
            'finish_date.date' => 'Format tanggal berakhir tidak valid.',
            'finish_date.after_or_equal' => 'Tanggal berakhir tidak boleh sebelum tanggal mulai.',
            'selectedMajors.required' => 'Minimal pilih satu jurusan.',
            'quota.min' => 'Kuota minimal adalah 1.',
            'selectedMajors.array' => 'Format jurusan tidak valid.',
        ]);

        try {
            DB::beginTransaction();

            $partner = Partner::updateOrCreate(
                ['id' => $this->partnerId],
                [
                    'name' => $this->name,
                    'email' => $this->email,
                    'phone_number' => $this->phone_number,
                    'quota' => $this->quota,
                    'criteria' => implode(', ', $this->criteriaList),
                    'address' => $this->address,
                    'start_date' => $this->start_date,
                    'finish_date' => $this->finish_date,
                ]
            );

            $partner->majors()->sync($this->selectedMajors);

            DB::commit();

            session()->flash('success', 'Data mitra berhasil disimpan.');
            return $this->redirectRoute('partners.index', navigate: true);
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.partners.form', [
            'allMajors' => Major::all()
        ]);
    }
}
