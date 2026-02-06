<x-slot:title>
    Dashboard
</x-slot:title>

<x-ui.table
    :columns="['Nama Mitra', 'Kuota', 'Kriteria', 'Jurusan', 'Aksi']"
    :rows="[
        [
            'PT Teknologi Nusantara',
            '10',
            'Disiplin & Komitmen',
            'RPL',
            view('components.ui.actions', [
                'actions' => [
                    ['label' => 'Detail'],
                    ['label' => 'Edit'],
                    ['label' => 'Terminate'],
                ]
            ])->render(),
        ],
        [
            'CV Digital Solusi',
            '5',
            'Siap Kerja Lapangan',
            'TKJ',
            view('components.ui.actions', [
                'actions' => [
                    ['label' => 'Detail'],
                    ['label' => 'Ajukan'],
                ]
            ])->render(),
        ],
    ]" />