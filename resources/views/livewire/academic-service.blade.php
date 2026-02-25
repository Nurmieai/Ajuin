<div class="flex flex-col gap-4">
    <x-ui.breadcrumbs :items="[
            'Layanan Akademik' => [
                'url' => route('academic-service'),
                'icon' => 'academic-cap'
            ],
            'Edit Mitra' => [
            'url' => null,
            'icon' => 'edit'
        ],
        ]" />
    <div class="flex w-full join">
        @role('teacher')
            <a class="btn btn-primary flex-1 join-item">Ajukan Surat</a>
            <a class="btn btn-warning flex-1 join-item">Cek Pengajuan Surat</a>
        @endrole

        @role('student')
            <a class="btn btn-ghost flex-1 join-item"
            href="{{ route('student.submission-manage') }}">
                Cek Pengajuan PKL
            </a>
            <a class="btn btn-success flex-1 join-item"
            href="{{ route('student.ulasan-pkl') }}">
                Ulasan PKL
            </a>
        @endrole
    </div>
</div>
