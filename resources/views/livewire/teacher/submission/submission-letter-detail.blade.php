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
    <div class="max-w-3xl mx-auto bg-white p-10 text-sm leading-relaxed text-black print:shadow-none shadow-md">

        {{-- KOP SURAT --}}
        <div class="flex items-center border-b-4 border-black pb-3 mb-6 gap-4">
            <div class="flex-shrink-0">
                <img src="{{ asset('images/logo-sekolah.png') }}" alt="Logo Sekolah" class="h-20 w-20 object-contain">
            </div>
            <div class="flex-1 text-center leading-tight">
                <p class="text-xs font-semibold uppercase tracking-wide">PEMERINTAH DAERAH PROVINSI JAWA BARAT</p>
                <p class="text-xs font-semibold uppercase tracking-wide">DINAS PENDIDIKAN</p>
                <p class="text-xs font-semibold uppercase tracking-wide">{{ $school->foundation_name }}</p>
                <p class="text-base font-extrabold uppercase tracking-wide">{{ $school->name }}</p>
                <p class="text-sm font-bold uppercase">AKREDITASI " {{ $school->accreditation }} "</p>
                <p class="text-xs uppercase tracking-wide">{{ $school->majors_label }}</p>
                <p class="text-xs">NPSN : {{ $school->npsn }}&nbsp;&nbsp;NSS : {{ $school->nss }}</p>
                <p class="text-xs">{{ $school->address }}</p>
                <p class="text-xs">Tlp:{{ $school->phone }}. Email:{{ $school->email }} Web:{{ $school->website }}</p>
            </div>
            <div class="flex-shrink-0">
                <img src="{{ asset('images/logo-smk.png') }}" alt="Logo SMK" class="h-20 w-20 object-contain">
            </div>
        </div>

        {{-- NOMOR, PRIHAL, LAMPIRAN --}}
        <div class="mb-5">
            <table>
                <tr>
                    <td class="w-24 align-top">Nomor</td>
                    <td class="align-top">: {{ $submission->latestLetter?->letter_number ?? 'k=' . date('Y') }}</td>
                </tr>
                <tr>
                    <td class="align-top">Prihal</td>
                    <td class="align-top">: Permohonan Praktik Kerja Lapangan</td>
                </tr>
                <tr>
                    <td class="align-top">Lampiran</td>
                    <td class="align-top">: 1 Lembar</td>
                </tr>
            </table>
        </div>

        {{-- ALAMAT TUJUAN --}}
        <div class="mb-5">
            <p>Kepada Yth.</p>
            <p class="font-bold">Pimpinan {{ $submission->company_name }}</p>
            <p>Di Tempat</p>
        </div>

        {{-- ISI SURAT --}}
        <div class="text-justify space-y-4">
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

        {{-- TANDA TANGAN --}}
        <div class="flex justify-end mt-10">
            <div class="text-center">
                <p>{{ \Carbon\Carbon::parse($submission->updated_at)->translatedFormat('d F Y') }}</p>
                <p>Kepala Sekolah</p>
                @if($school->signature_image)
                    <img src="{{ asset('storage/' . $school->signature_image) }}" alt="TTD" class="h-16 mx-auto my-2">
                @else
                    <div class="h-16"></div>
                @endif
                <p class="font-bold underline">{{ $school->principal_name }}</p>
                <p>NUPTK.{{ $school->principal_nuptk }}</p>
            </div>
        </div>
    </div>

    {{-- ===================== HALAMAN 2: LAMPIRAN ===================== --}}
    <div class="max-w-3xl mx-auto bg-white p-10 text-sm leading-relaxed text-black print:shadow-none shadow-md mt-6">

        <p class="font-bold mb-4">Lampiran</p>

        {{-- A. NAMA CALON PESERTA PKL --}}
        <p class="font-bold mb-2">A. Nama Calon Peserta PKL</p>
        <table class="w-full border-collapse border border-black mb-6 text-center">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border border-black px-3 py-1 w-10">No</th>
                    <th class="border border-black px-3 py-1">Nama</th>
                    <th class="border border-black px-3 py-1">Program Keahlian</th>
                    <th class="border border-black px-3 py-1">Konsentrasi Keahlian</th>
                </tr>
            </thead>
            <tbody>
                @foreach($groupSubmissions as $i => $s)
                <tr>
                    <td class="border border-black px-3 py-1">{{ $i + 1 }}</td>
                    <td class="border border-black px-3 py-1 text-left">{{ $s->user->fullname }}</td>
                    <td class="border border-black px-3 py-1">{{ $s->user->major?->program_name ?? $s->user->major?->name ?? '-' }}</td>
                    <td class="border border-black px-3 py-1">{{ $s->user->major?->concentration ?? $s->user->major?->name ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- B. HAK SMK DAN DUNIA KERJA --}}
        <p class="font-bold mb-2">B. Hak SMK {{ $school->short_name }} dan Dunia Kerja</p>
        <p class="mb-2">Secara umum hak SMK {{ $school->short_name }} dan dunia kerja yaitu:</p>
        <ol class="list-decimal list-inside space-y-1 mb-4 pl-2">
            <li>Dunia kerja berhak untuk menerima CV, Portofolio dan surat pengajuan PKL dari calon peserta PKL.</li>
            <li>Dunia kerja berhak untuk melakukan interview pada calon peserta PKL.</li>
            <li>Setelah melakukan interview dunia kerja berhak untuk menolak dan menerima peserta PKL.</li>
            <li>Pihak perusahaan berhak membuat kesepakatan dengan peserta PKL terkait aturan kerja.</li>
            <li>Pada saat pelaksanaan PKL, dunia kerja berhak untuk mengembalikan peserta PKL ke pihak sekolah apabila melanggar kesepakatan yang telah dibuat antara peserta PKL dengan pihak perusahaan.</li>
            <li>Pihak sekolah berhak untuk mengajukan permohonan melaksanakan PKL di dunia kerja.</li>
        </ol>
        <p class="mb-4 italic">Untuk hak dunia kerja dan SMK akan dikirim ketika peserta PKL di terima di dunia kerja.</p>

        {{-- C. KEWAJIBAN SETELAH DITERIMA --}}
        <p class="font-bold mb-2">C. Kewajiban SMK {{ $school->short_name }} dan Dunia Kerja Setelah Peserta PKL Diterima di Dunia Kerja</p>

        <p class="font-semibold mb-1">1. Kewajiban SMK {{ $school->short_name }}</p>
        <ol class="list-[lower-alpha] list-inside space-y-1 mb-3 pl-4">
            <li>Perencanaan PKL.</li>
            <li>Membuat nota kesepahaman dengan institusi dunia kerja.</li>
            <li>Mengantarkan dan menyerahkan peserta didik kepada institusi dunia kerja.</li>
            <li>Melakukan monitoring pelaksanaan PKL.</li>
            <li>Menjemput peserta PKL di akhir masa pelaksanaan PKL.</li>
        </ol>

        <p class="font-semibold mb-1">2. Kewajiban Dunia Kerja</p>
        <ol class="list-[lower-alpha] list-inside space-y-1 mb-4 pl-4">
            <li>Perencanaan PKL.</li>
            <li>Membuat nota kesepahaman dengan SMK.</li>
            <li>Menerima peserta PKL yang dinyatakan layak.</li>
            <li>Merekomendasikan akomodasi bagi peserta PKL.</li>
            <li>Memberitahukan fasilitas/insentif yang dapat diberikan institusi dunia kerja kepada peserta PKL (disesuaikan dengan aturan dari Dunia Kerja).</li>
            <li>Menunjuk instruktur untuk membimbing, mengarahkan dan meningkatkan potensi peserta PKL agar menjalankan tugas sebaik-baiknya.</li>
            <li>Memberikan sertifikat keikutsertaan PKL.</li>
        </ol>

        {{-- D. KEWAJIBAN PESERTA PKL --}}
        <p class="font-bold mb-2">D. Kewajiban Peserta PKL</p>
        <ol class="list-decimal list-inside space-y-1 pl-2">
            <li>Menaati peraturan/tata tertib di dunia kerja.</li>
            <li>Melaksanakan PKL sesuai dengan waktu yang telah ditentukan.</li>
            <li>Menjaga nama baik sekolah.</li>
            <li>Menjaga nama baik dunia kerja.</li>
            <li>Peserta didik tidak menjadi pemimpin projek (<em>project leader</em>), peserta didik dalam PKL hanya bertugas sebagai tenaga pendukung, bukan tenaga utama.</li>
        </ol>
    </div>

</div>
