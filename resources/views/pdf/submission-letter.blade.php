<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            color: #000;
            padding: 40px 60px;
        }
        .header {
            display: table;
            width: 100%;
            border-bottom: 3px double #000;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .header-logo {
            display: table-cell;
            width: 80px;
            vertical-align: middle;
            text-align: center;
        }
        .header-logo img {
            width: 70px;
            height: 70px;
        }
        .header-text {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
        }
        .header-text h2 {
            font-size: 16pt;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .header-text h3 { font-size: 12pt; }
        .header-text p { font-size: 10pt; margin-top: 2px; }

        .judul {
            text-align: center;
            margin: 24px 0 6px;
        }
        .judul h4 {
            font-size: 13pt;
            text-decoration: underline;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .nomor {
            text-align: center;
            font-size: 11pt;
            margin-bottom: 24px;
        }

        .opening {
            margin-bottom: 12px;
            line-height: 1.8;
        }

        table.data {
            margin: 8px 0 16px 20px;
            border-collapse: collapse;
        }
        table.data td {
            padding: 3px 6px;
            vertical-align: top;
            font-size: 12pt;
        }
        table.data td.label { width: 160px; }
        table.data td.sep   { width: 12px; }

        .closing {
            line-height: 1.8;
            margin-bottom: 10px;
        }

        .ttd-wrap {
            width: 100%;
            margin-top: 30px;
        }
        .ttd-wrap table {
            width: 100%;
        }
        .ttd-box {
            text-align: center;
            width: 200px;
        }
        .ttd-box .space { height: 75px; }
        .ttd-box .nama {
            font-weight: bold;
            text-decoration: underline;
        }
        .ttd-box .nip { font-size: 10pt; }

        .footer-note {
            margin-top: 40px;
            font-size: 9pt;
            border-top: 1px solid #bbb;
            padding-top: 5px;
            color: #555;
        }
    </style>
</head>
<body>

    {{-- ===== KOP SURAT ===== --}}
    <div class="header">
        <div class="header-logo">
            {{-- Ganti path logo sesuai project kamu --}}
            {{-- <img src="{{ public_path('images/logo-sekolah.png') }}"> --}}
        </div>
        <div class="header-text">
            <h2>SMK [Nama Sekolah]</h2>
            <h3>Program Keahlian Teknik Informatika & Rekayasa Perangkat Lunak</h3>
            <p>Jl. [Alamat Lengkap Sekolah], [Kota] | Telp: (xxx) xxxx-xxxx</p>
            <p>Email: info@sekolah.sch.id | Website: www.sekolah.sch.id</p>
        </div>
    </div>

    {{-- ===== JUDUL ===== --}}
    <div class="judul">
        <h4>Surat Pengantar Praktik Kerja Lapangan</h4>
    </div>
    <div class="nomor">
        Nomor: {{ $submission->letter_number ?? '---/PKL/' . \Carbon\Carbon::now()->year }}
    </div>

    {{-- ===== PEMBUKA ===== --}}
    <div class="opening">
        <p>Yang bertanda tangan di bawah ini, Kepala SMK [Nama Sekolah], dengan ini menerangkan bahwa:</p>
    </div>

    <table class="data">
        <tr>
            <td class="label">Nama Siswa</td>
            <td class="sep">:</td>
            <td><strong>{{ $submission->user->fullname }}</strong></td>
        </tr>
        <tr>
            <td class="label">NIS</td>
            <td class="sep">:</td>
            <td>{{ $submission->user->nis ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Kelas</td>
            <td class="sep">:</td>
            <td>{{ $submission->user->class ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Program Keahlian</td>
            <td class="sep">:</td>
            <td>Teknik Informatika</td>
        </tr>
    </table>

    <div class="closing">
        <p>Bermaksud untuk melaksanakan <strong>Praktik Kerja Lapangan (PKL)</strong> di instansi/perusahaan berikut:</p>
    </div>

    <table class="data">
        <tr>
            <td class="label">Nama Perusahaan</td>
            <td class="sep">:</td>
            <td><strong>{{ $submission->company_name }}</strong></td>
        </tr>
        <tr>
            <td class="label">Alamat Perusahaan</td>
            <td class="sep">:</td>
            <td>{{ $submission->company_address ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal Mulai</td>
            <td class="sep">:</td>
            <td>{{ \Carbon\Carbon::parse($submission->start_date)->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal Selesai</td>
            <td class="sep">:</td>
            <td>{{ \Carbon\Carbon::parse($submission->finish_date)->translatedFormat('d F Y') }}</td>
        </tr>
    </table>

    <div class="closing">
        <p>
            Demikian surat pengantar ini kami buat dengan sebenarnya untuk dapat dipergunakan
            sebagaimana mestinya. Atas perhatian dan kerjasamanya, kami ucapkan terima kasih.
        </p>
    </div>

    {{-- ===== TANDA TANGAN ===== --}}
    <div class="ttd-wrap">
        <table>
            <tr>
                <td style="width:50%; text-align:center;">
                    <div class="ttd-box">
                        <p>[Kota], {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                        <p>Wali Kelas,</p>
                        <div class="space"></div>
                        <p class="nama">{{ $submission->user->teacher->fullname ?? '______________________' }}</p>
                        <p class="nip">NIP. {{ $submission->user->teacher->nip ?? '-' }}</p>
                    </div>
                </td>
                <td style="width:50%; text-align:center;">
                    <div class="ttd-box">
                        <p>&nbsp;</p>
                        <p>Kepala Sekolah,</p>
                        <div class="space"></div>
                        <p class="nama">______________________</p>
                        <p class="nip">NIP. -</p>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer-note">
        Dokumen ini dicetak secara otomatis oleh sistem pada
        {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WIB.
        Status pengajuan disetujui pada: {{ \Carbon\Carbon::parse($submission->updated_at)->translatedFormat('d F Y') }}.
    </div>

</body>
</html>
