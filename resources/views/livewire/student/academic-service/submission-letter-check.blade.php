<x-slot:title>
    Cek Pengajuan Surat
</x-slot:title>

<div class="flex flex-col gap-4">

    <x-ui.breadcrumbs :items="[
        'Layanan Akademik' => [
            'url' => route('student.academic-service'),
            'icon' => 'academic-cap'
        ],
        'Cek Pengajuan Surat' => [
            'url' => '#',
            'icon' => 'document-text'
        ],
    ]" />

    <x-ui.pageheader
        title="Cek Pengajuan Surat PKL"
        subtitle="Pantau status pengajuan PKL dan surat kamu." />

    @if (!$submission)
        <div class="alert alert-warning shadow-sm text-white border-none">
            <div>
                <h3 class="font-bold">Belum Ada Pengajuan</h3>
                <div class="text-xs">Silakan buat pengajuan PKL terlebih dahulu.</div>
            </div>
            <a wire:navigate href="{{ route('student.submission-create') }}" class="btn btn-sm btn-warning">
                Buat Pengajuan
            </a>
        </div>

    @else

        @if ($submission->status === 'approved')
            <div class="alert alert-info shadow-sm dark:bg-blue-500 text-white border-none">
                <div>
                    <h3 class="font-bold">Pengajuan PKL Disetujui</h3>
                    <div class="text-xs">
                        Disetujui pada {{ \Carbon\Carbon::parse($submission->approved_at)->translatedFormat('d F Y, H:i') }} WIB
                    </div>
                </div>
            </div>
        @endif

        <div class="overflow-x-auto">
            <x-ui.table :columns="['Perusahaan', 'Tanggal Mulai', 'Tanggal Selesai', 'Waktu Pengajuan', 'Status PKL', 'Status Surat', 'Aksi']">
                <tr class="text-slate-700 dark:text-slate-300 transition-colors duration-200 hover:bg-slate-50 dark:hover:bg-slate-900">

                    <td class="px-4 py-3 font-medium">
                        {{ $submission->company_name }}
                    </td>

                    <td class="px-4 py-3 text-sm">
                        {{ \Carbon\Carbon::parse($submission->start_date)->translatedFormat('d F Y') }}
                    </td>

                    <td class="px-4 py-3 text-sm">
                        {{ \Carbon\Carbon::parse($submission->finish_date)->translatedFormat('d F Y') }}
                    </td>

                    <td class="px-4 py-3">
                        <div class="flex flex-col gap-1">
                            <span class="text-xs text-slate-400">
                                {{ \Carbon\Carbon::parse($submission->created_at)->translatedFormat('d F Y, H:i') }}
                            </span>
                            @if ($submission->created_at->ne($submission->updated_at))
                                <span class="text-xs text-warning italic">
                                    ✎ Diedit: {{ \Carbon\Carbon::parse($submission->updated_at)->translatedFormat('d F Y, H:i') }}
                                </span>
                            @endif
                        </div>
                    </td>

                    <td class="px-4 py-3">
                        @if ($submission->status === 'submitted')
                            <span class="badge badge-warning badge-sm">Menunggu</span>
                        @elseif ($submission->status === 'approved')
                            <span class="badge badge-success badge-sm">Disetujui</span>
                        @elseif ($submission->status === 'rejected')
                            <span class="badge badge-error badge-sm">Ditolak</span>
                        @elseif ($submission->status === 'cancelled')
                            <span class="badge badge-ghost badge-sm">Dibatalkan</span>
                        @endif
                    </td>

                    <td class="px-4 py-3">
                        @if ($submission->status !== 'approved')
                            <span class="badge badge-ghost badge-sm whitespace-nowrap">Belum bisa diajukan</span>
                        @elseif (!$letter)
                            <span class="badge badge-ghost badge-sm whitespace-nowrap">Belum diajukan</span>
                        @elseif ($letter->status === 'requested')
                            <span class="badge badge-warning badge-sm whitespace-nowrap">Menunggu Diproses</span>
                        @elseif ($letter->status === 'approved')
                            <span class="badge badge-success badge-sm whitespace-nowrap">Surat Siap</span>
                        @elseif ($letter->status === 'rejected')
                            <span class="badge badge-error badge-sm whitespace-nowrap">Ditolak</span>
                        @endif
                    </td>

                    <td class="px-4 py-3">
                        @if ($submission->status === 'approved' && (!$letter || $letter->status === 'rejected'))
                            <x-ui.actions :actions="[
                                [
                                    'icon' => 'plus',
                                    'color' => 'blue',
                                    'label' => !$letter ? 'Ajukan Surat' : 'Ajukan Ulang',
                                    'event' => 'requestLetter()',
                                ],
                            ]" />
                        @elseif ($letter?->status === 'approved')
                            <x-ui.actions :actions="[
                                [
                                    'icon' => 'eye',
                                    'color' => 'blue',
                                    'label' => 'Lihat Surat',
                                    'url' => route('student.submission-letter', ['submission' => $submission->id]),
                                ],
                            ]" />
                        @else
                            <span class="text-slate-400 text-xs">—</span>
                        @endif
                    </td>

                </tr>
            </x-ui.table>
        </div>

    @endif

    <x-ui.toast />
</div>
