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

    <div class="max-w-3xl mx-auto bg-white p-10 text-sm leading-relaxed text-black">

        <div class="text-center mb-6">
            <h2 class="text-lg font-bold uppercase">
                SMK NAMA SEKOLAH
            </h2>

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
            <p class="font-semibold">
                {{ $submission->company_name }}
            </p>
            <p>
                {{ $submission->company_address }}
            </p>
            <p>di Tempat</p>
        </div>

        <div class="mb-6 text-justify">

            <p class="mb-4">
                Dengan hormat,
            </p>

            <p class="mb-4">
                Dalam rangka pelaksanaan Praktik Kerja Lapangan (PKL),
                kami bermaksud mengajukan permohonan agar siswa berikut
                dapat diterima:
            </p>

            <table class="w-full ml-4 mb-4">

                <tr>
                    <td class="w-40">Nama</td>
                    <td>: {{ $submission->user->fullname }}</td>
                </tr>

                <tr>
                    <td>NISN</td>
                    <td>: {{ $submission->user->nisn }}</td>
                </tr>

                <tr>
                    <td>Jurusan</td>
                    <td>: {{ $submission->user->major?->name }}</td>
                </tr>

                <tr>
                    <td>Jenis Kelamin</td>
                    <td>
                        : {{ $submission->user->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}
                    </td>
                </tr>

                <tr>
                    <td>Tanggal Lahir</td>
                    <td>: {{ $submission->user->birth_date }}</td>
                </tr>

                <tr>
                    <td>Alamat</td>
                    <td>: {{ $submission->user->alamat_tinggal }}</td>
                </tr>

            </table>
            <p class="mb-4">
                Pelaksanaan PKL mulai tanggal
                <b>
                    {{ \Carbon\Carbon::parse($submission->start_date)->translatedFormat('d F Y') }}
                </b>
                sampai
                <b>
                    {{ \Carbon\Carbon::parse($submission->finish_date)->translatedFormat('d F Y') }}
                </b>.
            </p>

            <p>
                Demikian surat ini disampaikan.
            </p>
        </div>

        <div class="flex justify-end mt-12">

            <div class="text-center">
                <p>
                    {{ now()->translatedFormat('d F Y') }}
                </p>

                <p class="mt-16 font-semibold underline">
                    Nama Kepala Sekolah
                </p>

                <p>
                    NIP. 123456789
                </p>
            </div>

        </div>
    </div>
</div>
