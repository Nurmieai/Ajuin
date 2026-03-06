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

        $pdf = Pdf::loadView('pdf.submission-letter', compact('submission'))
            ->setPaper('a4', 'portrait');

        $filename = 'Surat_PKL_' . str_replace(' ', '_', $submission->user->fullname) . '_' . now()->format('Ymd') . '.pdf';

        return $pdf->download($filename);
    }
}
