<?php

namespace App\Livewire\Student;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Submission;

class Profile extends Component
{
    public $fullname, $nisn, $email, $major_id, $gender, $birth_date, $nomor_handphone, $alamat_tinggal, $nama_tempat_pkl, $cv_url, $portfolio_url;

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
        $this->cv_url = $user->link_cv;
        $this->portfolio_url = $user->link_portofolio;

        $approved = $user->submissions()
            ->where('status', 'approved')
            ->first();

        $this->nama_tempat_pkl = $approved?->company_name;
    }

    public function save()
{
    $this->validate(
        [
            'fullname'          => ['required', 'string', 'min:3', 'max:255'],
            'gender'            => ['required', 'in:L,P', 'not_in:""'],
            'birth_date'        => ['required', 'date', 'before:today'],
            'nomor_handphone'   => ['nullable', 'digits_between:12,13', 'phone:ID'],
            'alamat_tinggal'    => ['nullable', 'string', 'min:5', 'max:500'],
            'cv_url'            => ['nullable', 'url', 'min:3', 'max:255'],
            'portfolio_url'     => ['nullable', 'url', 'min:3', 'max:255'],
        ],
        [
            'fullname.required' => 'Nama wajib diisi.',
            'fullname.min'      => 'Nama minimal 3 karakter.',
            'fullname.max'      => 'Nama maksimal 255 karakter.',

            'gender.required'   => 'Jenis kelamin wajib dipilih.',

            'birth_date.required' => 'Tanggal lahir wajib diisi.',
            'birth_date.before'   => 'Tanggal lahir tidak valid.',

            'nomor_handphone.phone'          => 'Masukkan nomor telepon yang valid.',
            'nomor_handphone.digits_between' => 'Nomor HP harus 12–13 digit angka.',

            'alamat_tinggal.min' => 'Alamat terlalu pendek.',

            'cv_url.url'        => 'Link CV harus berupa URL yang valid.',
            'portfolio_url.url' => 'Link portofolio harus berupa URL yang valid.',
        ]
    );
        try {
            Auth::user()->update([
                'fullname'          => $this->fullname,
                'gender'            => $this->gender,
                'birth_date'        => $this->birth_date,
                'nomor_handphone'   => $this->nomor_handphone,
                'alamat_tinggal'    => $this->alamat_tinggal,
                'cv_url'            => $this->cv_url,
                'portfolio_url'     => $this->portfolio_url,
            ]);

            $this->dispatch('toast', message: 'Profil berhasil diperbarui!', type: 'success');
        } catch (\Exception $e) {
            $this->dispatch('toast', message: 'Terjadi kesalahan: ' . $e->getMessage(), type: 'error');
        }
    }

    public function render()
    {
        return view('livewire.student.profile');
    }
}
