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

        <x-ui.table :columns="[
            'Nama Siswa',
            'Perusahaan',
            'Tanggal Mulai',
            'Tanggal Selesai',
            'Status',
            'Aksi'
        ]">

        @forelse ($submissions as $submission)
        <tr class="text-slate-700 dark:text-slate-300
                   transition-colors duration-200
                   hover:bg-slate-50 dark:hover:bg-slate-900">

            <td>
                <span class="font-medium">
                    {{ $submission->user->fullname }}
                </span>
            </td>

            <td>
                {{ $submission->company_name }}
            </td>

            <td>
                {{ $submission->start_date->format('d/m/Y') }}
            </td>

            <td>
                {{ $submission->finish_date->format('d/m/Y') }}
            </td>

            <td>
                @if($submission->status == 'pending')
                    <span class="badge badge-warning badge-sm">
                        Menunggu
                    </span>
                @elseif($submission->status == 'approved')
                    <span class="badge badge-success badge-sm">
                        Diterima
                    </span>
                @else
                    <span class="badge badge-error badge-sm">
                        Ditolak
                    </span>
                @endif
            </td>

            <td>
                <x-ui.actions :actions="[
                    [
                        'label' => 'Detail',
                        'icon' => 'info',
                        'color' => 'blue',
                        'url' => route('teacher.submission-letter-detail', $submission->id)
                    ],

                    [
                        'label' => 'Download Surat',
                        'icon' => 'arrow-down-tray',
                        'color' => 'indigo',
                        'url' => route('teacher.submission-letter', $submission->id),
                        'target' => '_blank'
                    ],

                    [
                        'label' => 'Terima',
                        'icon' => 'check',
                        'color' => 'green',
                        'event' => 'confirmApprove(' . $submission->id . ')'
                    ],

                    [
                        'label' => 'Tolak',
                        'icon' => 'x',
                        'color' => 'red',
                        'event' => 'confirmReject(' . $submission->id . ')'
                    ],
                ]" />
            </td>
        </tr>

        @empty
            <tr>
                <td colspan="6" class="text-center py-8">

                    <div class="flex flex-col items-center gap-3
                                text-slate-500 dark:text-slate-400">

                        <span class="text-sm font-medium">
                            Belum ada pengajuan surat PKL
                        </span>

                    </div>

                </td>
            </tr>
        @endforelse
    </x-ui.table>

    </div>

    <div class="mt-4 flex justify-center">
        {{ $submissions->links() }}
    </div>

    <dialog id="approveModal" class="modal" wire:ignore.self>

        <div class="modal-box">

            <h3 class="font-bold text-lg">
                Konfirmasi Terima Pengajuan
            </h3>

            <p class="py-4">
                Yakin ingin menerima pengajuan dari
                <span class="font-semibold text-primary">
                    {{ $selectedSubmission?->user->fullname ?? '' }}
                </span> ?
            </p>

            <div class="modal-action">
                <button class="btn btn-ghost" onclick="approveModal.close()">
                    Batal
                </button>

                <button
                    class="btn btn-success"
                    wire:click="approve"
                    wire:loading.attr="disabled">

                    <span wire:loading.remove wire:target="approve">
                        Ya, Terima
                    </span>

                    <span wire:loading wire:target="approve"
                        class="loading loading-spinner loading-sm">
                    </span>
                </button>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>

        <dialog id="rejectModal" class="modal" wire:ignore.self>
            <div class="modal-box">
                <h3 class="font-bold text-lg">
                    Konfirmasi Tolak Pengajuan
                </h3>

                <p class="py-4">
                    Yakin ingin menolak pengajuan dari
                    <span class="font-semibold text-error">
                        {{ $selectedSubmission?->user->fullname ?? '' }}
                    </span> ?
                </p>

                <div class="modal-action">
                    <button class="btn btn-ghost" onclick="rejectModal.close()">
                        Batal
                    </button>

                    <button
                        class="btn btn-error"
                        wire:click="reject"
                        wire:loading.attr="disabled">

                        <span wire:loading.remove wire:target="reject">
                            Ya, Tolak
                        </span>

                        <span wire:loading wire:target="reject"
                            class="loading loading-spinner loading-sm">
                        </span>
                    </button>
                </div>
            </div>

            <form method="dialog" class="modal-backdrop">
                <button>close</button>
            </form>

        </dialog>
    <x-ui.toast />
</div>

@script
    <script>

    $wire.on('open-approve-modal', () => {
        approveModal.showModal();
    });

    $wire.on('close-approve-modal', () => {
        approveModal.close();
    });

    $wire.on('open-reject-modal', () => {
        rejectModal.showModal();
    });

    $wire.on('close-reject-modal', () => {
        rejectModal.close();
    });

    </script>
@endscript
