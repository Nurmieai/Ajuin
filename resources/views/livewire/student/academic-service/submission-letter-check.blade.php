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
            <tr class="text-slate-700 dark:text-slate-300 theme-transition hover:bg-slate-50 dark:hover:bg-slate-900">

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

                {{-- Kolom Status PKL --}}
                <td class="px-4 py-3">
                    <x-ui.badge :variant="$submission->getStatusVariant()" size="sm">
                        {{ $submission->getStatusLabel() }}
                    </x-ui.badge>
                </td>

                {{-- Kolom Status Surat --}}
                <td class="px-4 py-3">
                    @php
                    $letterStatus = 'neutral';
                    $letterLabel = 'Belum bisa diajukan';

                    if ($submission->status === 'approved') {
                    if (!$letter) {
                    $letterStatus = 'neutral';
                    $letterLabel = 'Belum diajukan';
                    } else {
                    $letterStatus = match($letter->status) {
                    'requested' => 'warning',
                    'approved' => 'success',
                    'rejected' => 'danger',
                    default => 'neutral'
                    };
                    $letterLabel = match($letter->status) {
                    'requested' => 'Menunggu Diproses',
                    'approved' => 'Surat Siap',
                    'rejected' => 'Ditolak',
                    default => 'Unknown'
                    };
                    }
                    }
                    @endphp

                    <x-ui.badge :variant="$letterStatus" size="sm" class="whitespace-nowrap">
                        {{ $letterLabel }}
                    </x-ui.badge>
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
</div>