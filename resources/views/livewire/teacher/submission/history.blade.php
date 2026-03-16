<x-slot:title>
    Riwayat Pengajuan
</x-slot:title>

<div class="flex flex-col gap-4">
    <x-ui.breadcrumbs :items="[
        'Pengajuan' => [
            'url' => route('teacher.submission-manage'),
            'icon' => 'edit'
        ],
        'History' => [
            'url' => route('teacher.submission-history'),
            'icon' => 'edit'
        ],
    ]" />

    <x-ui.pageheader
        title="Riwayat Pengajuan PKL"
        subtitle="Kelola pengajuan PKL siswa yang sudah dikelola" />

<div class="flex flex-row gap-4 justify-between items-center">
    <x-ui.search />

</div>

    <div class="overflow-x-auto">
        <x-ui.table :columns="['Nama Siswa', 'Nama Perusahaan', 'Tanggal Mulai', 'Tanggal Selesai', 'Aksi']">
            @forelse ($submissionsApproved as $submission)
                <tr>
                <td>{{ $submission->user->fullname }}</td>
                <td>{{ $submission->company_name}}</td>
                <td>{{ $submission->start_date->format('d/m/Y') }}</td>
                <td>{{ $submission->finish_date->format('d/m/Y') }}</td>
                <td>
                    <x-ui.actions :actions="[
                    [
                        'label' => 'Detail Modal',
                        'icon' => 'info',
                        'color' => 'blue',
                        'event' => ''
                    ],
                    [
                        'label' => 'Batalkan',
                        'icon' => 'x',
                        'color' => 'red',
                        'event' => ''
                    ],
                ]" />
                </td>
                </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center py-8">
                    <div class="flex flex-col items-center gap-3 text-slate-500 dark:text-slate-400">
                        <span class="text-sm font-medium">Belum ada pengajuan yang perlu ditinjau</span>
                    </div>
                </td>
            </tr>
            @endforelse
        </x-ui.table>
    </div>
</div>