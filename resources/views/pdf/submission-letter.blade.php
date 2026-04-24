<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 20mm 20mm 20mm 25mm; }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            color: #000;
            line-height: 1.6;
        }

        .page { width: auto; padding: 2cm; }
        .page-break { page-break-before: always; }

        /* KOP */
        .kop { display: table; width: 100%; border-bottom: 4px solid #000; padding-bottom: 8px; margin-bottom: 14px; }
        .kop-logo { display: table-cell; width: 85px; vertical-align: middle; text-align: center; }
        .kop-logo img { width: 80px; height: 80px; }
        .kop-text { display: table-cell; vertical-align: middle; text-align: center; padding: 0 6px; line-height: 1.35; }
        .kop-text p { margin: 0; }
        .prov        { font-size: 9pt; font-weight: bold; text-transform: uppercase; }
        .nama-sekolah{ font-size: 17pt; font-weight: bold; text-transform: uppercase; letter-spacing: 0.3px; line-height: 1.2; }
        .akreditasi  { font-size: 12pt; font-weight: bold; text-transform: uppercase; }
        .jurusan     { font-size: 8pt; text-transform: uppercase; }
        .npsn        { font-size: 8pt; }
        .alamat      { font-size: 7.5pt; }

        /* NOMOR SURAT */
        .info-surat { margin-bottom: 12px; }
        .info-surat table { border-collapse: collapse; }
        .info-surat td { font-size: 11pt; padding: 1px 0; vertical-align: top; }
        .info-surat td.label { width: 80px; }
        .info-surat td.sep   { width: 14px; }

        /* TUJUAN */
        .tujuan { margin-bottom: 12px; line-height: 1.6; font-size: 11pt; }
        .tujuan p { margin: 0; }

        /* ISI */
        .isi { font-size: 11pt; text-align: justify; line-height: 1.6; }
        .isi p { margin-bottom: 8px; }

        /* TTD */
        .ttd-wrap { margin-top: 20px; width: 100%; }
        .ttd-wrap table { width: 100%; }
        .ttd-cell { text-align: center; vertical-align: top; font-size: 11pt; line-height: 1.6; }
        .ttd-cell p { margin: 0; }
        .ttd-space { height: 55px; }
        .ttd-nama  { font-weight: bold; text-decoration: underline; }
        .ttd-nuptk { font-size: 10pt; }

        /* LAMPIRAN */
        .lampiran-judul { font-size: 11pt; font-weight: bold; margin-bottom: 12px; }
        .lampiran-section { margin-bottom: 10px; }
        .lampiran-section .sub-judul { font-size: 11pt; font-weight: bold; margin-bottom: 4px; }
        .lampiran-section p { font-size: 11pt; margin-bottom: 3px; line-height: 1.55; }

        table.tabel-peserta { width: 100%; border-collapse: collapse; font-size: 10.5pt; margin-bottom: 6px; }
        table.tabel-peserta th,
        table.tabel-peserta td { border: 1px solid #000; padding: 4px 8px; text-align: center; vertical-align: middle; }
        table.tabel-peserta th { background-color: #e8e8e8; font-weight: bold; }
        table.tabel-peserta td.left { text-align: left; }

        .ol-decimal { margin-left: 20px; font-size: 11pt; line-height: 1.55; padding-left: 4px; }
        .ol-alpha   { margin-left: 30px; font-size: 11pt; line-height: 1.55; }
        .italic-note { font-style: italic; font-size: 11pt; margin-top: 4px; line-height: 1.55; }
    </style>
</head>
<body>

@php
    $romanMonths  = ['I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII'];
    $letterDate   = $submission->letter?->created_at ?? now();
    $romanMonth   = $romanMonths[$letterDate->month - 1];
    $letterNumber = $submission->letter?->letter_number
        ?? ('101.30/102.10.235/SMK.MP/' . $romanMonth . '/' . $letterDate->format('Y'));
@endphp

{{-- HALAMAN 1 --}}
<div class="page">

    <div class="kop">
        <div class="kop-logo">
            <img src="{{ public_path('assets/img/logo/a.png') }}">
        </div>
        <div class="kop-text">
            <p class="prov">PEMERINTAH DAERAH PROVINSI JAWA BARAT</p>
            <p class="prov">DINAS PENDIDIKAN</p>
            <p class="prov">{{ $school->foundation_name }}</p>
            <p class="nama-sekolah">{{ $school->name }}</p>
            <p class="akreditasi">AKREDITASI &ldquo; {{ $school->accreditation }} &rdquo;</p>
            <p class="jurusan">{{ $school->majors_label }}</p>
            <p class="npsn">NPSN : {{ $school->npsn }}&nbsp;&nbsp;&nbsp;NSS : {{ $school->nss }}</p>
            <p class="alamat">{{ $school->address }}</p>
            <p class="alamat">Tlp:{{ $school->phone }}. Email:{{ $school->email }} Web:{{ $school->website }}</p>
        </div>
        <div class="kop-logo">
            <img src="{{ public_path('assets/img/logo/b.png') }}">
        </div>
    </div>

    <div class="info-surat">
        <table>
            <tr>
                <td class="label">Nomor</td>
                <td class="sep">:</td>
                <td>{{ $letterNumber }}</td>
            </tr>
            <tr>
                <td class="label">Prihal</td>
                <td class="sep">:</td>
                <td>Permohonan Praktik Kerja Lapangan</td>
            </tr>
            <tr>
                <td class="label">Lampiran</td>
                <td class="sep">:</td>
                <td>1 Lembar</td>
            </tr>
        </table>
    </div>

    <div class="tujuan">
        <p>Kepada Yth.</p>
        <p><strong>Pimpinan {{ $submission->company_name }}</strong></p>
        <p>Di Tempat</p>
    </div>

    <div class="isi">
        <p>Dengan Hormat,</p>
        <p>
            Dalam rangka pelaksanaan Pendidikan Vokasi terkait dengan program <em>link</em> and <em>match</em> guna
            meningkatkan kompetensi peserta didik, diwajibkan untuk melaksanakan Praktik Kerja Lapangan
            (PKL). Oleh karena itu kami mengajukan permohonan untuk melaksanakan praktik kerja lapangan di
            <strong>{{ $submission->company_name }}</strong> yang Bapak/Ibu pimpin.
        </p>
        <p>
            Adapun pelaksanaan PKL kami rencanakan pada bulan
            <strong>{{ \Carbon\Carbon::parse($submission->start_date)->translatedFormat('F') }}</strong>
            sampai dengan bulan
            <strong>{{ \Carbon\Carbon::parse($submission->finish_date)->translatedFormat('F') }}</strong>
            tahun <strong>{{ \Carbon\Carbon::parse($submission->finish_date)->format('Y') }}</strong>
            atau sesuai dengan waktu yang Bapak/Ibu tentukan. Selama kegiatan PKL berlangsung, sekolah
            akan tetap melakukan pemantauan dan evaluasi sebagai bentuk pembinaan.
        </p>
        <p>Demikian surat permohonan ini kami ajukan, atas perhatiannya kami ucapkan terima kasih.</p>
    </div>

    <div class="ttd-wrap">
        <table>
            <tr>
                <td style="width: 60%;"></td>
                <td class="ttd-cell" style="width: 40%;">
                    <p>{{ \Carbon\Carbon::parse($submission->updated_at)->translatedFormat('d F Y') }}</p>
                    <p>Kepala Sekolah</p>
                    @if(!empty($school->signature_image))
                        <div style="height: 10px;"></div>
                        <img src="{{ public_path('storage/' . $school->signature_image) }}"
                             style="height: 55px; margin: 0 auto;" alt="TTD">
                    @else
                        <div class="ttd-space"></div>
                    @endif
                    <p class="ttd-nama">{{ $school->principal_name }}</p>
                    <p class="ttd-nuptk">NUPTK.{{ $school->principal_nuptk }}</p>
                </td>
            </tr>
        </table>
    </div>

</div>

{{-- HALAMAN 2 --}}
<div class="page page-break">

    <p class="lampiran-judul">Lampiran</p>

    <div class="lampiran-section">
        <p class="sub-judul">A. Nama Calon Peserta PKL</p>
        <table class="tabel-peserta">
            <thead>
                <tr>
                    <th style="width: 30px;">No</th>
                    <th>Nama</th>
                    <th>Program Keahlian</th>
                    <th>Konsentrasi Keahlian</th>
                </tr>
            </thead>
            <tbody>
                @foreach($groupSubmissions as $i => $s)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td class="left">{{ $s->user->fullname }}</td>
                    <td>{{ $s->user->major?->program_name ?? $s->user->major?->name ?? '-' }}</td>
                    <td>{{ $s->user->major?->concentration ?? $s->user->major?->name ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="lampiran-section">
        <p class="sub-judul">B. Hak SMK {{ $school->short_name }} dan Dunia Kerja</p>
        <p>Secara umum hak SMK {{ $school->short_name }} dan dunia kerja yaitu:</p>
        <ol type="1" class="ol-decimal">
            <li>Dunia kerja berhak untuk menerima CV, Portofolio dan surat pengajuan PKL dari calon peserta PKL.</li>
            <li>Dunia kerja berhak untuk melakukan interview pada calon peserta PKL.</li>
            <li>Setelah melakukan interview dunia kerja berhak untuk menolak dan menerima peserta PKL.</li>
            <li>Pihak perusahaan berhak membuat kesepakatan dengan peserta PKL terkait aturan kerja.</li>
            <li>Pada saat pelaksanaan PKL, dunia kerja berhak untuk mengembalikan peserta PKL ke pihak sekolah apabila melanggar kesepakatan yang telah dibuat antara peserta PKL dengan pihak perusahaan.</li>
            <li>Pihak sekolah berhak untuk mengajukan permohonan melaksanakan PKL di dunia kerja.</li>
        </ol>
        <p class="italic-note">Untuk hak dunia kerja dan SMK akan dikirim ketika peserta PKL di terima di dunia kerja.</p>
    </div>

    <div class="lampiran-section">
        <p class="sub-judul">C. Kewajiban SMK {{ $school->short_name }} dan Dunia Kerja Setelah Peserta PKL Diterima di Dunia Kerja</p>

        <p><strong>1. Kewajiban SMK {{ $school->short_name }}</strong></p>
        <ol type="a" class="ol-alpha">
            <li>Perencanaan PKL.</li>
            <li>Membuat nota kesepahaman dengan institusi dunia kerja.</li>
            <li>Mengantarkan dan menyerahkan peserta didik kepada institusi dunia kerja.</li>
            <li>Melakukan monitoring pelaksanaan PKL.</li>
            <li>Menjemput peserta PKL di akhir masa pelaksanaan PKL.</li>
        </ol>

        <p style="margin-top: 8px;"><strong>2. Kewajiban Dunia Kerja</strong></p>
        <ol type="a" class="ol-alpha">
            <li>Perencanaan PKL.</li>
            <li>Membuat nota kesepahaman dengan SMK.</li>
            <li>Menerima peserta PKL yang dinyatakan layak.</li>
            <li>Merekomendasikan akomodasi bagi peserta PKL.</li>
            <li>Memberitahukan fasilitas/insentif yang dapat diberikan institusi dunia kerja kepada peserta PKL (disesuaikan dengan aturan dari Dunia Kerja).</li>
            <li>Menunjuk instruktur untuk membimbing, mengarahkan dan meningkatkan potensi peserta PKL agar menjalankan tugas sebaik-baiknya.</li>
            <li>Memberikan sertifikat keikutsertaan PKL.</li>
        </ol>
    </div>

    <div class="lampiran-section">
        <p class="sub-judul">D. Kewajiban Peserta PKL</p>
        <ol type="1" class="ol-decimal">
            <li>Menaati peraturan/tata tertib di dunia kerja.</li>
            <li>Melaksanakan PKL sesuai dengan waktu yang telah ditentukan.</li>
            <li>Menjaga nama baik sekolah.</li>
            <li>Menjaga nama baik dunia kerja.</li>
            <li>Peserta didik tidak menjadi pemimpin projek (<em>project leader</em>), peserta didik dalam PKL hanya bertugas sebagai tenaga pendukung, bukan tenaga utama.</li>
        </ol>
    </div>

</div>

</body>
</html>
