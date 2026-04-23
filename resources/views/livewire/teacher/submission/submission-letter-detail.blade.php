<x-slot:title>
    Detail Surat PKL
</x-slot:title>

<div class="flex flex-col gap-4">

    <x-ui.breadcrumbs :items="[
        'Surat PKL' => [
            'url' => route('teacher.submission-letter'),
            'icon' => 'document'
        ],
        'Detail Surat' => [
            'url' => '#',
            'icon' => 'document-text'
        ]
    ]" />

    <x-ui.pageheader
        title="Detail Surat PKL"
        subtitle="Periksa Surat PKL" />

    {{-- ===================== HALAMAN 1: SURAT PERMOHONAN PKL ===================== --}}
    <div class="max-w-3xl mx-auto w-full bg-white shadow-md border border-gray-200 rounded-sm"
         style="padding: 3cm; font-family: 'Times New Roman', Times, serif; font-size: 11pt; color: #000; line-height: 1.6;">

        {{-- KOP SURAT --}}
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
            <div style="width: 68px; flex-shrink: 0; text-align: center;">
                @if(file_exists(public_path('images/logo-sekolah.png')))
                    <img src="{{ asset('images/logo-sekolah.png') }}" style="width: 68px; height: 68px;">
                @else
                    <div style="width: 68px; height: 68px; border: 2px solid #ccc; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 8pt; color: #999; text-align: center;">Logo</div>
                @endif
            </div>
            <div style="flex: 1; text-align: center;">
                <p style="font-size: 9.5pt; font-weight: bold; text-transform: uppercase; margin: 0; line-height: 1.5;">PEMERINTAH DAERAH PROVINSI JAWA BARAT</p>
                <p style="font-size: 9.5pt; font-weight: bold; text-transform: uppercase; margin: 0; line-height: 1.5;">DINAS PENDIDIKAN</p>
                <p style="font-size: 9.5pt; font-weight: bold; text-transform: uppercase; margin: 0; line-height: 1.5;">{{ $school->foundation_name }}</p>
                <p style="font-size: 15pt; font-weight: bold; text-transform: uppercase; margin: 0; line-height: 1.3; letter-spacing: 0.5px;">{{ $school->name }}</p>
                <p style="font-size: 11pt; font-weight: bold; text-transform: uppercase; margin: 0;">AKREDITASI &ldquo;{{ $school->accreditation }}&rdquo;</p>
                <p style="font-size: 8.5pt; text-transform: uppercase; margin: 0; line-height: 1.4;">{{ $school->majors_label }}</p>
                <p style="font-size: 8.5pt; margin: 0; line-height: 1.5;">NPSN : {{ $school->npsn }}&nbsp;&nbsp;&nbsp;NSS : {{ $school->nss }}</p>
                <p style="font-size: 8pt; margin: 0; line-height: 1.5;">{{ $school->address }}</p>
                <p style="font-size: 8pt; margin: 0; line-height: 1.5;">Tlp:{{ $school->phone }}. Email:{{ $school->email }} Web:{{ $school->website }}</p>
            </div>
            <div style="width: 68px; flex-shrink: 0; text-align: center;">
                @if(file_exists(public_path('images/logo-smk.png')))
                    <img src="{{ asset('images/logo-smk.png') }}" style="width: 68px; height: 68px;">
                @else
                    <div style="width: 68px; height: 68px; border: 2px solid #ccc; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 8pt; color: #999; text-align: center;">Logo</div>
                @endif
            </div>
        </div>

        {{-- Garis kop --}}
        <div style="border-bottom: 4px solid #000; margin-bottom: 16px;"></div>

        {{-- NOMOR, PRIHAL, LAMPIRAN --}}
        <div style="margin-bottom: 12px;">
            <table style="border-collapse: collapse; font-size: 11pt;">
                <tr>
                    <td style="width: 80px; padding: 1px 0; vertical-align: top;">Nomor</td>
                    <td style="width: 14px; padding: 1px 0; vertical-align: top;">:</td>
                    <td style="padding: 1px 0; vertical-align: top;">{{ $submission->latestLetter?->letter_number ?? ('101.30/102.10.235/SMK.MP/I/' . date('Y')) }}</td>
                </tr>
                <tr>
                    <td style="padding: 1px 0; vertical-align: top;">Prihal</td>
                    <td style="padding: 1px 0; vertical-align: top;">:</td>
                    <td style="padding: 1px 0; vertical-align: top;">Permohonan Praktik Kerja Lapangan</td>
                </tr>
                <tr>
                    <td style="padding: 1px 0; vertical-align: top;">Lampiran</td>
                    <td style="padding: 1px 0; vertical-align: top;">:</td>
                    <td style="padding: 1px 0; vertical-align: top;">1 Lembar</td>
                </tr>
            </table>
        </div>

        {{-- ALAMAT TUJUAN --}}
        <div style="margin-bottom: 12px; line-height: 1.6;">
            <p style="margin: 0;">Kepada Yth.</p>
            <p style="margin: 0; font-weight: bold;">Pimpinan {{ $submission->company_name }}</p>
            <p style="margin: 0;">Di Tempat</p>
        </div>

        {{-- ISI SURAT --}}
        <div style="text-align: justify; line-height: 1.6;">
            <p style="margin-bottom: 8px;">Dengan Hormat,</p>
            <p style="margin-bottom: 8px;">
                Dalam rangka pelaksanaan Pendidikan Vokasi terkait dengan program <em>link</em> and <em>match</em> guna
                meningkatkan kompetensi peserta didik, diwajibkan untuk melaksanakan Praktik Kerja Lapangan
                (PKL). Oleh karena itu kami mengajukan permohonan untuk melaksanakan praktik kerja lapangan di
                <strong>{{ $submission->company_name }}</strong> yang Bapak/Ibu pimpin.
            </p>
            <p style="margin-bottom: 8px;">
                Adapun pelaksanaan PKL kami rencanakan pada bulan
                <strong>{{ \Carbon\Carbon::parse($submission->start_date)->translatedFormat('F') }}</strong>
                sampai dengan bulan
                <strong>{{ \Carbon\Carbon::parse($submission->finish_date)->translatedFormat('F') }}</strong>
                tahun <strong>{{ \Carbon\Carbon::parse($submission->finish_date)->format('Y') }}</strong>
                atau sesuai dengan waktu yang Bapak/Ibu tentukan. Selama kegiatan PKL berlangsung, sekolah
                akan tetap melakukan pemantauan dan evaluasi sebagai bentuk pembinaan.
            </p>
            <p style="margin-bottom: 8px;">Demikian surat permohonan ini kami ajukan, atas perhatiannya kami ucapkan terima kasih.</p>
        </div>

        {{-- TANDA TANGAN --}}
        <div style="margin-top: 20px; display: flex; justify-content: flex-end;">
            <div style="text-align: center; min-width: 200px;">
                <p style="margin: 0;">{{ \Carbon\Carbon::parse($submission->updated_at)->translatedFormat('d F Y') }}</p>
                <p style="margin: 0;">Kepala Sekolah</p>
                @if($school->signature_image)
                    <img src="{{ asset('storage/' . $school->signature_image) }}" alt="TTD" style="height: 55px; margin: 8px auto;">
                @else
                    <div style="height: 55px;"></div>
                @endif
                <p style="margin: 0; font-weight: bold; text-decoration: underline;">{{ $school->principal_name }}</p>
                <p style="margin: 0; font-size: 10pt;">NUPTK.{{ $school->principal_nuptk }}</p>
            </div>
        </div>
    </div>

    {{-- ===================== HALAMAN 2: LAMPIRAN ===================== --}}
    <div class="max-w-3xl mx-auto w-full bg-white shadow-md border border-gray-200 rounded-sm mt-6"
         style="padding: 3cm; font-family: 'Times New Roman', Times, serif; font-size: 11pt; color: #000; line-height: 1.6;">

        <p style="font-weight: bold; margin-bottom: 12px;">Lampiran</p>

        {{-- A. NAMA CALON PESERTA PKL --}}
        <div style="margin-bottom: 12px;">
            <p style="font-weight: bold; margin-bottom: 6px;">A. Nama Calon Peserta PKL</p>
            <table style="width: 100%; border-collapse: collapse; font-size: 10.5pt;">
                <thead>
                    <tr>
                        <th style="border: 1px solid #000; padding: 4px 8px; text-align: center; background: #e8e8e8; width: 30px;">No</th>
                        <th style="border: 1px solid #000; padding: 4px 8px; text-align: center; background: #e8e8e8;">Nama</th>
                        <th style="border: 1px solid #000; padding: 4px 8px; text-align: center; background: #e8e8e8;">Program Keahlian</th>
                        <th style="border: 1px solid #000; padding: 4px 8px; text-align: center; background: #e8e8e8;">Konsentrasi Keahlian</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($groupSubmissions as $i => $s)
                    <tr>
                        <td style="border: 1px solid #000; padding: 4px 8px; text-align: center;">{{ $i + 1 }}</td>
                        <td style="border: 1px solid #000; padding: 4px 8px; text-align: left;">{{ $s->user->fullname }}</td>
                        <td style="border: 1px solid #000; padding: 4px 8px; text-align: center;">{{ $s->user->major?->program_name ?? $s->user->major?->name ?? '-' }}</td>
                        <td style="border: 1px solid #000; padding: 4px 8px; text-align: center;">{{ $s->user->major?->concentration ?? $s->user->major?->name ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- B. HAK SMK DAN DUNIA KERJA --}}
        <div style="margin-bottom: 10px;">
            <p style="font-weight: bold; margin-bottom: 4px;">B. Hak SMK {{ $school->short_name }} dan Dunia Kerja</p>
            <p style="margin-bottom: 4px;">Secara umum hak SMK {{ $school->short_name }} dan dunia kerja yaitu:</p>
            <ol style="margin-left: 20px; font-size: 11pt; line-height: 1.55; padding-left: 4px;">
                <li>Dunia kerja berhak untuk menerima CV, Portofolio dan surat pengajuan PKL dari calon peserta PKL.</li>
                <li>Dunia kerja berhak untuk melakukan interview pada calon peserta PKL.</li>
                <li>Setelah melakukan interview dunia kerja berhak untuk menolak dan menerima peserta PKL.</li>
                <li>Pihak perusahaan berhak membuat kesepakatan dengan peserta PKL terkait aturan kerja.</li>
                <li>Pada saat pelaksanaan PKL, dunia kerja berhak untuk mengembalikan peserta PKL ke pihak sekolah apabila melanggar kesepakatan yang telah dibuat antara peserta PKL dengan pihak perusahaan.</li>
                <li>Pihak sekolah berhak untuk mengajukan permohonan melaksanakan PKL di dunia kerja.</li>
            </ol>
            <p style="font-style: italic; margin-top: 4px;">Untuk hak dunia kerja dan SMK akan dikirim ketika peserta PKL di terima di dunia kerja.</p>
        </div>

        {{-- C. KEWAJIBAN SETELAH DITERIMA --}}
        <div style="margin-bottom: 10px;">
            <p style="font-weight: bold; margin-bottom: 4px;">C. Kewajiban SMK {{ $school->short_name }} dan Dunia Kerja Setelah Peserta PKL Diterima di Dunia Kerja</p>

            <p style="font-weight: bold; margin-bottom: 2px;">1. Kewajiban SMK {{ $school->short_name }}</p>
            <ol type="a" style="margin-left: 30px; font-size: 11pt; line-height: 1.55;">
                <li>Perencanaan PKL.</li>
                <li>Membuat nota kesepahaman dengan institusi dunia kerja.</li>
                <li>Mengantarkan dan menyerahkan peserta didik kepada institusi dunia kerja.</li>
                <li>Melakukan monitoring pelaksanaan PKL.</li>
                <li>Menjemput peserta PKL di akhir masa pelaksanaan PKL.</li>
            </ol>

            <p style="font-weight: bold; margin-top: 8px; margin-bottom: 2px;">2. Kewajiban Dunia Kerja</p>
            <ol type="a" style="margin-left: 30px; font-size: 11pt; line-height: 1.55;">
                <li>Perencanaan PKL.</li>
                <li>Membuat nota kesepahaman dengan SMK.</li>
                <li>Menerima peserta PKL yang dinyatakan layak.</li>
                <li>Merekomendasikan akomodasi bagi peserta PKL.</li>
                <li>Memberitahukan fasilitas/insentif yang dapat diberikan institusi dunia kerja kepada peserta PKL (disesuaikan dengan aturan dari Dunia Kerja).</li>
                <li>Menunjuk instruktur untuk membimbing, mengarahkan dan meningkatkan potensi peserta PKL agar menjalankan tugas sebaik-baiknya.</li>
                <li>Memberikan sertifikat keikutsertaan PKL.</li>
            </ol>
        </div>

        {{-- D. KEWAJIBAN PESERTA PKL --}}
        <div style="margin-bottom: 10px;">
            <p style="font-weight: bold; margin-bottom: 4px;">D. Kewajiban Peserta PKL</p>
            <ol style="margin-left: 20px; font-size: 11pt; line-height: 1.55; padding-left: 4px;">
                <li>Menaati peraturan/tata tertib di dunia kerja.</li>
                <li>Melaksanakan PKL sesuai dengan waktu yang telah ditentukan.</li>
                <li>Menjaga nama baik sekolah.</li>
                <li>Menjaga nama baik dunia kerja.</li>
                <li>Peserta didik tidak menjadi pemimpin projek (<em>project leader</em>), peserta didik dalam PKL hanya bertugas sebagai tenaga pendukung, bukan tenaga utama.</li>
            </ol>
        </div>

    </div>

</div>
