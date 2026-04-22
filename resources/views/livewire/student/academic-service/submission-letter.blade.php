<x-slot:title>
    Surat Pengajuan PKL
</x-slot:title>

<div class="flex flex-col gap-6 pb-10">

    <x-ui.breadcrumbs :items="[
        'Layanan Akademik' => [
            'url' => route('student.academic-service'),
            'icon' => 'academic-cap'
        ],
        'Pengajuan Surat' => [
            'url' => '#',
            'icon' => 'document-text'
        ],
    ]" />

    {{-- Wrapper kertas surat --}}
    <div class="max-w-3xl mx-auto w-full">

        {{-- Info badge status --}}
        @if($submission->status === 'approved')
            <div class="mb-3 flex items-center gap-2 text-sm text-green-700 bg-green-50 border border-green-200 rounded-lg px-4 py-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Pengajuan PKL kamu telah <strong class="ml-1">disetujui</strong>. Surat ini adalah pratinjau resmi.
            </div>
        @elseif($submission->status === 'pending')
            <div class="mb-3 flex items-center gap-2 text-sm text-yellow-700 bg-yellow-50 border border-yellow-200 rounded-lg px-4 py-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Pengajuan PKL kamu sedang <strong class="ml-1">menunggu persetujuan</strong>.
            </div>
        @endif

        {{-- KERTAS SURAT --}}
        <div class="bg-white shadow-md border border-gray-200 rounded-sm"
             style="padding: 3cm; font-family: 'Times New Roman', Times, serif; font-size: 11pt; color: #000; line-height: 1.6;">

            {{-- KOP SURAT --}}
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 8px;">
                {{-- Logo kiri --}}
                <div style="width: 68px; flex-shrink: 0; text-align: center;">
                    @if(file_exists(public_path('images/logo-sekolah.png')))
                        <img src="{{ asset('images/logo-sekolah.png') }}" style="width: 68px; height: 68px;">
                    @else
                        <div style="width: 68px; height: 68px; border: 2px solid #ccc; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 8pt; color: #999; text-align: center;">Logo</div>
                    @endif
                </div>

                {{-- Teks kop --}}
                <div style="flex: 1; text-align: center;">
                    <p style="font-size: 9.5pt; font-weight: bold; text-transform: uppercase; margin: 0; line-height: 1.5;">PEMERINTAH DAERAH PROVINSI JAWA BARAT</p>
                    <p style="font-size: 9.5pt; font-weight: bold; text-transform: uppercase; margin: 0; line-height: 1.5;">DINAS PENDIDIKAN</p>
                    <p style="font-size: 9.5pt; font-weight: bold; text-transform: uppercase; margin: 0; line-height: 1.5;">YAYASAN MAHAPUTRA CERDAS UTAMA</p>
                    <p style="font-size: 15pt; font-weight: bold; text-transform: uppercase; margin: 0; line-height: 1.3; letter-spacing: 0.5px;">SMKS MAHAPUTRA CERDAS UTAMA</p>
                    <p style="font-size: 11pt; font-weight: bold; text-transform: uppercase; margin: 0;">AKREDITASI &ldquo;A&rdquo;</p>
                    <p style="font-size: 8.5pt; text-transform: uppercase; margin: 0; line-height: 1.4;">DESAIN KOMUNIKASI VISUAL &amp; PENGEMBANGAN PERANGKAT LUNAK DAN GIM</p>
                    <p style="font-size: 8.5pt; margin: 0; line-height: 1.5;">NPSN : 69949896 &nbsp;&nbsp;&nbsp; NSS : 402020828126</p>
                    <p style="font-size: 8pt; margin: 0; line-height: 1.5;">Jl. Katapang Andir, Km 4. Pasantren. Ds. Sukamukti. Kec. Katapang. Kab.Bandung Kode Pos:40971</p>
                    <p style="font-size: 8pt; margin: 0; line-height: 1.5;">Tlp:(022) 5893178. Email:smkmahaputracerdasutama@gmail.com Web:smkmahaputra.sch.id</p>
                </div>

                {{-- Logo kanan --}}
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

            {{-- NOMOR SURAT --}}
            <div style="margin-bottom: 12px;">
                <table style="border-collapse: collapse; font-size: 11pt;">
                    <tr>
                        <td style="width: 80px; padding: 1px 0; vertical-align: top;">Nomor</td>
                        <td style="width: 14px; padding: 1px 0; vertical-align: top;">:</td>
                        <td style="padding: 1px 0; vertical-align: top;">{{ $letter->letter_number ?? ('101.30/102.10.235/SMK.MP/I/' . \Carbon\Carbon::now()->format('Y')) }}</td>
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

                <p style="margin-bottom: 8px;">
                    Demikian surat permohonan ini kami ajukan, atas perhatiannya kami ucapkan terima kasih.
                </p>
            </div>

            {{-- TANDA TANGAN --}}
            <div style="margin-top: 20px; display: flex; justify-content: flex-end;">
                <div style="text-align: center; min-width: 200px;">
                    <p style="margin: 0;">{{ \Carbon\Carbon::parse($submission->updated_at)->translatedFormat('d F Y') }}</p>
                    <p style="margin: 0;">Kepala Sekolah</p>
                    <div style="height: 55px;"></div>
                    <p style="margin: 0; font-weight: bold; text-decoration: underline;">Siti Robiah Adawiyah, S.Pd.</p>
                    <p style="margin: 0; font-size: 10pt;">NUPTK.1144748649300013</p>
                </div>
            </div>

            {{-- GARIS PEMISAH LAMPIRAN --}}
            <div style="border-top: 1px dashed #999; margin: 24px 0 16px;"></div>

            {{-- LAMPIRAN --}}
            <p style="font-weight: bold; margin-bottom: 12px;">Lampiran</p>

            {{-- A. Nama Calon Peserta --}}
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

            {{-- B. Hak SMK --}}
            <div style="margin-bottom: 10px;">
                <p style="font-weight: bold; margin-bottom: 4px;">B. Hak SMK Mahaputra dan Dunia Kerja</p>
                <p style="margin-bottom: 4px;">Secara umum hak SMK Mahaputra dan dunia kerja yaitu:</p>
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

            {{-- C. Kewajiban --}}
            <div style="margin-bottom: 10px;">
                <p style="font-weight: bold; margin-bottom: 4px;">C. Kewajiban SMK Mahaputra dan Dunia Kerja Setelah Peserta PKL Diterima di Dunia Kerja</p>

                <p style="font-weight: bold; margin-bottom: 2px;">1. Kewajiban SMK Mahaputra</p>
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

            {{-- D. Kewajiban Peserta --}}
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

        </div>{{-- end kertas surat --}}

    </div>

</div>
