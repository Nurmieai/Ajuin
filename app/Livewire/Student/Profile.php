<?php

namespace App\Livewire\Student;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Submission;

class Profile extends Component
{
    public $fullname;
    public $nisn;
    public $email;
    public $major_id;

    public $gender;
    public $birth_date;
    public $nomor_handphone;
    public $alamat_tinggal;

    public $nama_tempat_pkl;

    public function mount()
    {
        $user = Auth::user();

        $this->fullname = $user->fullname;
        $this->nisn = $user->nisn;
        $this->email = $user->email;
        $this->major_id = $user->major_id;

        $this->gender = $user->gender;
        $this->birth_date = $user->birth_date;

        if (!$this->birth_date) {
            $this->birth_date = '2010-01-01';
        }

        $this->nomor_handphone = $user->nomor_handphone;
        $this->alamat_tinggal = $user->alamat_tinggal;

        $approved = $user->submissions()
            ->where('status', 'approved')
            ->first();

        $this->nama_tempat_pkl = $approved?->company_name;
    }

    public function save()
    {
        $this->validate(
            [
                'fullname' => 'required|string|min:3|max:255',
                'gender' => ['required', 'in:L,P', 'not_in:""'],
                'birth_date' => 'required|date|before:today',
                'nomor_handphone' => [
                    'required',
                    'digits_between:12,13'
                ],
                'alamat_tinggal' => 'required|string|min:5|max:500',
            ],
            [
                'fullname.required' => 'Nama wajib diisi.',
                'fullname.min' => 'Nama minimal 3 karakter.',
                'fullname.max' => 'Nama maksimal 255 karakter.',

                'gender.required' => 'Jenis kelamin wajib dipilih.',

                'birth_date.required' => 'Tanggal lahir wajib diisi.',
                'birth_date.before' => 'Tanggal lahir tidak valid.',

                'nomor_handphone.required' => 'Nomor HP wajib diisi.',
                'nomor_handphone.digits_between' => 'Nomor HP harus 12–13 digit angka.',

                'alamat_tinggal.required' => 'Alamat wajib diisi.',
                'alamat_tinggal.min' => 'Alamat terlalu pendek.',
            ]
        );

        Auth::user()->update([
            'fullname' => $this->fullname,
            'gender' => $this->gender,
            'birth_date' => $this->birth_date,
            'nomor_handphone' => $this->nomor_handphone,
            'alamat_tinggal' => $this->alamat_tinggal,
        ]);

        session()->flash('success', 'Profile updated successfully.');
    }

    public function render()
    {
        return view('livewire.student.profile');
    }
}
