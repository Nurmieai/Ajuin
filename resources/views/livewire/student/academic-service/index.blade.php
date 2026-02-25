<div class="flex flex-col gap-4">
    <x-ui.breadcrumbs :items="[
            'Layanan Akademik' => [
                'url' => route('student.academic-service'),
                'icon' => 'academic-cap' 
            ], 
            'Edit Mitra' => [
            'url' => null,
            'icon' => 'edit'
        ],
        ]" />
    <div class="flex flex-row justify-between w-full join">
        <a class="btn  btn-primary w-1/4 join-item">Ajukan Surat</a>
        <a class="btn  btn-ghast w-1/4 join-item" href="{{ route('student.submission-manage') }}">Cek Pengajuan PKL</a>
        <a class="btn  btn-warning w-1/4 join-item">Cek Pengajuan surat</a>
        <a class="btn  btn-success w-1/4 join-item">Ulasan PKL</a>
    </div>
</div>