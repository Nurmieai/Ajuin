<?php

namespace App\Livewire\Teacher\Submission;

use App\Models\Submission;
use Livewire\Component;

class SubmissionLetterDetail extends Component
{
    public $submission;
    public $school;

    public function mount($id): void
    {
        $this->submission = Submission::with(['user.major'])->findOrFail($id);

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
            'submission' => $this->submission,
            'school'     => $this->school,
        ]);
    }
}
