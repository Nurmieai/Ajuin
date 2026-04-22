<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use Barryvdh\DomPDF\Facade\Pdf;

class SubmissionLetterController extends Controller
{
    public function download($id)
    {
        $submission = Submission::with(['user.major', 'letter'])->findOrFail($id);

        if ($submission->status !== 'approved') {
            abort(403, 'Surat hanya bisa diunduh setelah pengajuan diterima.');
        }

        \Carbon\Carbon::setLocale('id');

        // Cari semua siswa dalam grup yang sama
        // Mitra: sama partner_id
        // Mandiri: sama company_name + start_date + finish_date
        if ($submission->partner_id) {
            $groupSubmissions = Submission::with(['user.major'])
                ->where('partner_id', $submission->partner_id)
                ->where('status', 'approved')
                ->oldest()
                ->get();
        } else {
            $groupSubmissions = Submission::with(['user.major'])
                ->whereNull('partner_id')
                ->where('company_name', $submission->company_name)
                ->whereDate('start_date', $submission->start_date)
                ->whereDate('finish_date', $submission->finish_date)
                ->where('status', 'approved')
                ->oldest()
                ->get();
        }

        $school = (object) [
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

        $pdf = Pdf::loadView('pdf.submission-letter', [
            'submission'       => $submission,
            'groupSubmissions' => $groupSubmissions,
            'school'           => $school,
        ])
            ->setPaper('a4', 'portrait')
            ->setOption('margin-top', 20)
            ->setOption('margin-right', 25)
            ->setOption('margin-bottom', 20)
            ->setOption('margin-left', 25);

        // Nama file pakai nama perusahaan jika lebih dari 1 siswa
        if ($groupSubmissions->count() > 1) {
            $filename = 'Surat_PKL_'
                . str_replace([' ', ',', '.'], '_', $submission->company_name)
                . '_'
                . now()->format('Ymd')
                . '.pdf';
        } else {
            $filename = 'Surat_PKL_'
                . str_replace(' ', '_', $submission->user->fullname)
                . '_'
                . now()->format('Ymd')
                . '.pdf';
        }

        return $pdf->download($filename);
    }
}
