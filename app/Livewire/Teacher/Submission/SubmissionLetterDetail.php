<?php

namespace App\Livewire\Teacher\Submission;

use App\Models\Submission;
use Livewire\Component;

class SubmissionLetterDetail extends Component
{
    public $submission;
    public $school;
    public $groupSubmissions;

    public function mount($id): void
    {
        $this->submission = Submission::with(['user.major', 'latestLetter'])->findOrFail($id);

        // Ambil semua siswa dalam grup yang sama
        if ($this->submission->partner_id) {
            $this->groupSubmissions = Submission::with(['user.major'])
                ->where('partner_id', $this->submission->partner_id)
                ->where('status', 'approved')
                ->oldest()
                ->get();
        } else {
            $this->groupSubmissions = Submission::with(['user.major'])
                ->whereNull('partner_id')
                ->where('company_name', $this->submission->company_name)
                ->whereDate('start_date', $this->submission->start_date)
                ->whereDate('finish_date', $this->submission->finish_date)
                ->where('status', 'approved')
                ->oldest()
                ->get();
        }

        // Fallback kalau belum ada yang approved (misal guru buka detail sebelum approve)
        if ($this->groupSubmissions->isEmpty()) {
            $this->groupSubmissions = collect([$this->submission]);
        }

        $this->school = (object) [
            'foundation_name' => 'YAYASAN MAHAPUTRA CERDAS UTAMA',
            'name'            => 'SMKS MAHAPUTRA CERDAS UTAMA',
            'short_name'      => 'Mahaputra',
            'accreditation'   => 'A',
            'majors_label'    => 'DESAIN KOMUNIKASI VISUAL & PENGEMBANGAN PERANGKAT LUNAK DAN GIM',
            'npsn'            => '69949896',
            'nss'             => '402020828126',
            'address'         => 'Jl. Katapang Andir, Km 4. Pasantren. Ds. Sukamukti. Kec. Katapang. Kab.Bandung Kode Pos:40971',
            'phone'           => '(022) 5893178',
            'email'           => 'smkmahaputracerdasutama@gmail.com',
            'website'         => 'smkmahaputra.sch.id',
            'principal_name'  => 'Siti Robiah Adawiyah, S.Pd.',
            'principal_nuptk' => '1144748649300013',
            'signature_image' => null,
        ];
    }

    public function render()
    {
        return view('livewire.teacher.submission.submission-letter-detail', [
            'submission'       => $this->submission,
            'school'           => $this->school,
            'groupSubmissions' => $this->groupSubmissions,
        ]);
    }
}
