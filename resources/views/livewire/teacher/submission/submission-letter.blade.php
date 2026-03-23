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
            <tr class="text-slate-700 dark:text-slate-300 transition-colors duration-200 hover:bg-slate-50 dark:hover:bg-slate-900
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
                    @if ($letter->status === 'requested')
                    <span class="badge badge-warning badge-sm whitespace-nowrap">Menunggu</span>
                    @elseif ($letter->status === 'approved')
                    <span class="badge badge-success badge-sm whitespace-nowrap">Diterima</span>
                    @elseif ($letter->status === 'rejected')
                    <span class="badge badge-error badge-sm whitespace-nowrap">Ditolak</span>
                    @endif
                    @if (!$isLatest)
                    <span class="badge badge-ghost badge-sm mt-1 whitespace-nowrap">Tidak Aktif</span>
                    @endif
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

    <dialog id="approveModal" class="modal" wire:ignore.self>
        <div class="modal-box">
            <h3 class="font-bold text-lg">Konfirmasi Terima Surat</h3>
            <p class="py-4">
                Yakin ingin menerima surat PKL dari
                <span class="font-semibold text-primary">{{ $selectedSubmission?->user->fullname ?? '' }}</span>?
            </p>
            <div class="modal-action">
                <button class="btn btn-ghost" onclick="approveModal.close()">Batal</button>
                <button class="btn btn-success" wire:click="approve" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="approve">Ya, Terima</span>
                    <span wire:loading wire:target="approve" class="loading loading-spinner loading-sm"></span>
                </button>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop"><button>close</button></form>
    </dialog>

    <dialog id="rejectModal" class="modal" wire:ignore.self>
        <div class="modal-box">
            <h3 class="font-bold text-lg">Konfirmasi Tolak Surat</h3>
            <p class="py-4">
                Yakin ingin menolak surat PKL dari
                <span class="font-semibold text-error">{{ $selectedSubmission?->user->fullname ?? '' }}</span>?
            </p>
            <div class="modal-action">
                <button class="btn btn-ghost" onclick="rejectModal.close()">Batal</button>
                <button class="btn btn-error" wire:click="reject" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="reject">Ya, Tolak</span>
                    <span wire:loading wire:target="reject" class="loading loading-spinner loading-sm"></span>
                </button>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop"><button>close</button></form>
    </dialog>

</div>

@script
<script>
    $wire.on('open-approve-modal', () => approveModal.showModal());
    $wire.on('close-approve-modal', () => approveModal.close());
    $wire.on('open-reject-modal', () => rejectModal.showModal());
    $wire.on('close-reject-modal', () => rejectModal.close());
</script>
@endscript