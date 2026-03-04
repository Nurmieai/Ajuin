<x-slot:title>
    Surat Pengajuan PKL
</x-slot:title>
<div class="flex flex-col gap-4">
    <x-ui.breadcrumbs :items="[
            'Layanan Akademik' => [
                'url' => route('student.academic-service'),
                'icon' => 'academic-cap'
            ],
        ]" />
    <div class="max-w-3xl mx-auto bg-white p-10 text-sm leading-relaxed text-black">

        <div class="text-center mb-6">
            <h2 class="text-lg font-bold uppercase">SMK NAMA SEKOLAH</h2>
            <p>Alamat Sekolah Lengkap</p>
            <p>Telp: 08xxxxxxxx | Email: sekolah@email.com</p>
            <hr class="mt-4 border-black">
        </div>

        <div class="mb-6">
            <p>Nomor : 001/PKL/SMK/{{ date('Y') }}</p>
            <p>Lampiran : -</p>
            <p>Perihal : Permohonan Praktik Kerja Lapangan</p>
        </div>

        <div class="mb-6">
            <p>Kepada Yth.</p>
            <p class="font-semibold">{{ $submission->company_name }}</p>
            <p>{{ $submission->company_address }}</p>
            <p>di Tempat</p>
        </div>

        <div class="mb-6 text-justify">
            <p class="mb-4">
                Dengan hormat,
            </p>

            <p class="mb-4">
                Dalam rangka pelaksanaan Praktik Kerja Lapangan (PKL) bagi siswa/siswi
                SMK NAMA SEKOLAH, kami bermaksud mengajukan permohonan kepada
                Bapak/Ibu agar dapat menerima siswa berikut:
            </p>

            <div class="ml-6 mb-4">
                <table class="w-full">
                    <tr>
                        <td class="w-40">Nama</td>
                        <td>: {{ auth()->user()->fullname }}</td>
                    </tr>
                    <tr>
                        <td>NISN</td>
                        <td>: {{ auth()->user()->nisn }}</td>
                    </tr>
                    <tr>
                        <td>Jurusan</td>
                        <td>: {{ auth()->user()->major?->name }}</td>
                    </tr>
                    <tr>
                        <td>Jenis Kelamin</td>
                        <td>: {{ auth()->user()->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                    </tr>
                    <tr>
                        <td>Tanggal Lahir</td>
                        <td>: {{ auth()->user()->birth_date }}</td>
                    </tr>
                    <tr>
                        <td>Alamat</td>
                        <td>: {{ auth()->user()->alamat_tinggal }}</td>
                    </tr>
                </table>
            </div>

            <p class="mb-4">
                Untuk melaksanakan kegiatan PKL mulai tanggal
                <span class="font-semibold">
                    {{ \Carbon\Carbon::parse($submission->start_date)->translatedFormat('d F Y') }}
                </span>
                sampai dengan
                <span class="font-semibold">
                    {{ \Carbon\Carbon::parse($submission->finish_date)->translatedFormat('d F Y') }}
                </span>.
            </p>

            <p class="mb-4">
                Besar harapan kami agar Bapak/Ibu berkenan memberikan kesempatan
                dan bimbingan kepada siswa tersebut.
            </p>

            <p>
                Demikian surat permohonan ini kami sampaikan. Atas perhatian dan
                kerja samanya kami ucapkan terima kasih.
            </p>
        </div>

            <div class="flex justify-end mt-12">
                <div class="text-center">
                    <p>{{ now()->translatedFormat('d F Y') }}</p>
                    <p class="mt-16 font-semibold underline">Nama Kepala Sekolah</p>
                    <p>NIP. 123456789</p>
                </div>
            </div>
        </div>
        <div class="mt-8 text-center">
            <a href="{{ route('student.profile') }}"
            class="btn btn-outline btn-info">
                Perbarui Data Pribadi
            </a>
        </div>
</div>
