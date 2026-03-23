<x-slot:title>
    Pengelolaan Surat PKL
</x-slot:title>

<div class="flex flex-col gap-4">

    <x-ui.breadcrumbs :items="[
        'Surat PKL' => [
            'url' => route('teacher.submission-letter'),
            'icon' => 'document-text'
        ],
    ]" />

    <x-ui.pageheader
        title="Pengelolaan Surat PKL"
        subtitle="Kelola surat pengajuan PKL siswa, unduh surat, serta terima atau tolak pengajuan." />

    <x-ui.search />

    <div class="overflow-x-auto">
        <x-ui.table :columns="['Nama Siswa', 'Perusahaan', 'Periode', 'Diajukan / Diedit', 'Status', 'Aksi']">
            @forelse ($letters as $letter)
            @php
            $submission = $letter->submission;
            $isLatest = in_array($letter->id, $latestLetterIds);
            @endphp
            <tr class="text-slate-700 dark:text-slate-300 theme-transition duration-200 hover:bg-slate-50 dark:hover:bg-slate-900
                {{ !$isLatest ? 'opacity-50' : '' }}">

                {{-- Nama Siswa --}}
                <td class="px-4 py-3">
                    <span class="font-medium">{{ $submission->user->fullname ?? 'N/A' }}</span>
                </td>

                {{-- Perusahaan --}}
                <td class="px-4 py-3">{{ $submission->company_name }}</td>

                {{-- Periode (gabung mulai & selesai) --}}
                <td class="px-4 py-3 text-sm">
                    {{ \Carbon\Carbon::parse($submission->start_date)->format('d/m/Y') }}
                    —
                    {{ \Carbon\Carbon::parse($submission->finish_date)->format('d/m/Y') }}
                </td>

                {{-- Diajukan / Diedit --}}
                <td class="px-4 py-3">
                    <div class="flex flex-col gap-1">
                        <span class="text-xs text-slate-400">
                            📅 {{ \Carbon\Carbon::parse($submission->created_at)->translatedFormat('d F Y, H:i') }}
                        </span>
                        @if ($submission->created_at->ne($submission->updated_at))
                        <span class="text-xs text-warning italic">
                            ✎ Diedit: {{ \Carbon\Carbon::parse($submission->updated_at)->translatedFormat('d F Y, H:i') }}
                        </span>
                        @endif
                    </div>
                </td>

                {{-- Status --}}
                <td class="px-4 py-3">
                    <div class="flex flex-col gap-1">
                        {{-- Status Utama --}}
                        @if ($letter->status === 'requested')
                        <x-ui.badge variant="warning" size="sm" class="whitespace-nowrap w-fit">
                            Menunggu
                        </x-ui.badge>
                        @elseif ($letter->status === 'approved')
                        <x-ui.badge variant="success" size="sm" class="whitespace-nowrap w-fit">
                            Diterima
                        </x-ui.badge>
                        @elseif ($letter->status === 'rejected')
                        <x-ui.badge variant="danger" size="sm" class="whitespace-nowrap w-fit">
                            Ditolak
                        </x-ui.badge>
                        @endif

                        {{-- Indikator Tidak Aktif --}}
                        @if (!$isLatest)
                        <x-ui.badge variant="neutral" size="sm" class="whitespace-nowrap w-fit opacity-70">
                            Tidak Aktif
                        </x-ui.badge>
                        @endif
                    </div>
                </td>

                {{-- Aksi --}}
                <td class="px-4 py-3">
                    @if ($isLatest)
                    <x-ui.actions :actions="[
                            [
                                'label' => 'Detail',
                                'icon' => 'info',
                                'color' => 'blue',
                                'url' => route('teacher.submission-letter-detail', $submission->id),
                            ],
                            [
                                'label' => 'Download Surat',
                                'icon' => 'arrow-down-tray',
                                'color' => $letter->status === 'approved' ? 'indigo' : 'gray',
                                'url' => $letter->status === 'approved'
                                    ? route('teacher.submission-letter-download', $submission->id)
                                    : null,
                                'event' => $letter->status !== 'approved'
                                    ? 'downloadLetter(' . $submission->id . ')'
                                    : null,
                                'target' => '_blank',
                                'title' => $letter->status !== 'approved'
                                    ? 'Surat belum diterima'
                                    : 'Unduh Surat PKL',
                            ],
                            [
                                'label' => 'Terima',
                                'icon' => 'check',
                                'color' => $letter->status !== 'approved' ? 'green' : 'gray',
                                'event' => $letter->status !== 'approved'
                                    ? 'confirmApprove(' . $letter->id . ')'
                                    : null,
                                'title' => $letter->status === 'approved' ? 'Sudah diterima' : 'Terima surat',
                            ],
                            [
                                'label' => 'Tolak',
                                'icon' => 'x',
                                'color' => $letter->status !== 'rejected' ? 'red' : 'gray',
                                'event' => $letter->status !== 'rejected'
                                    ? 'confirmReject(' . $letter->id . ')'
                                    : null,
                                'title' => $letter->status === 'rejected' ? 'Sudah ditolak' : 'Tolak surat',
                            ],
                        ]" />
                    @else
                    {{-- Row lama — hanya tampil tombol detail saja --}}
                    <x-ui.actions :actions="[
                            [
                                'label' => 'Detail',
                                'icon' => 'info',
                                'color' => 'gray',
                                'url' => route('teacher.submission-letter-detail', $submission->id),
                            ],
                        ]" />
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center py-8">
                    <div class="flex flex-col items-center gap-3 text-slate-500 dark:text-slate-400">
                        <span class="text-sm font-medium">Belum ada pengajuan surat PKL</span>
                    </div>
                </td>
            </tr>
            @endforelse
        </x-ui.table>
    </div>

    <div class="mt-4 flex justify-center">
        {{ $letters->links('components.ui.pagination') }}
    </div>

    <x-ui.confirmation
        :open="$isApproveOpen"
        title="Konfirmasi Terima Surat"
        confirmText="Ya, Terima"
        confirmAction="approve"
        type="success">
        <x-slot:message>
            Yakin ingin menerima surat PKL dari
            <span class="font-semibold text-primary">{{ $selectedSubmission?->user->fullname ?? '' }}</span>?
        </x-slot:message>
    </x-ui.confirmation>

    <x-ui.confirmation
        :open="$isRejectOpen"
        title="Konfirmasi Tolak Surat"
        confirmText="Ya, Tolak"
        confirmAction="reject"
        type="danger">
        <x-slot:message>
            Yakin ingin menolak surat PKL dari
            <span class="font-semibold text-error">{{ $selectedSubmission?->user->fullname ?? '' }}</span>?
        </x-slot:message>
    </x-ui.confirmation>

</div>

@script
<script>
    $wire.on('open-approve-modal', () => approveModal.showModal());
    $wire.on('close-approve-modal', () => approveModal.close());
    $wire.on('open-reject-modal', () => rejectModal.showModal());
    $wire.on('close-reject-modal', () => rejectModal.close());
</script>
@endscript