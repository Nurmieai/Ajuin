<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use Barryvdh\DomPDF\Facade\Pdf;

class SubmissionLetterController extends Controller
{
    public function download($id)
    {
        $submission = Submission::with('user')->findOrFail($id);

        if ($submission->status !== 'approved') {
            abort(403, 'Surat hanya bisa diunduh setelah pengajuan diterima.');
        }

        \Carbon\Carbon::setLocale('id');

        $school = (object) [
            'foundation_name'  => 'YAYASAN MAHAPUTRA CERDAS UTAMA',
            'name'             => 'SMKS MAHAPUTRA CERDAS UTAMA',
            'short_name'       => 'Mahaputra',
            'accreditation'    => 'A',
            'majors_label'     => 'DESAIN KOMUNIKASI VISUAL & PENGEMBANGAN PERANGKAT LUNAK DAN GIM',
            'npsn'             => '69949896',
            'nss'              => '402020828126',
            'address'          => 'Jl. Katapang Andir, Km 4. Pasantren. Ds. Sukamukti. Kec. Katapang. Kab.Bandung Kode Pos:40971',
            'phone'            => '(022) 5893178',
            'email'            => 'smkmahaputracerdasutama@gmail.com',
            'website'          => 'smkmahaputra.sch.id',
            'principal_name'   => 'Siti Robiah Adawiyah, S.Pd.',
            'principal_nuptk'  => '1144748649300013',
            'signature_image'  => null, // isi path jika ada, misal: 'signatures/ttd-kepsek.png'
        ];

        $pdf = Pdf::loadView('pdf.submission-letter', compact('submission', 'school'))
            ->setPaper('a4', 'portrait');

        $filename = 'Surat_PKL_'
            . str_replace(' ', '_', $submission->user->fullname)
            . '_'
            . now()->format('Ymd')
            . '.pdf';

        return $pdf->download($filename);
    }
}
